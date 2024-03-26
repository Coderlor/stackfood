<?php

use App\Mail\OrderVerificationMail;
use App\Mail\PlaceOrder;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\AdminWallet;
use App\Models\DeliveryMan;
use App\Models\WalletPayment;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\CustomerLogic;
use Illuminate\Support\Facades\Mail;
use App\Models\SubscriptionTransaction;

if (! function_exists('translate')) {
    function translate($key, $replace = [])
    {
        if(strpos($key, 'validation.') === 0 || strpos($key, 'passwords.') === 0 || strpos($key, 'pagination.') === 0 || strpos($key, 'order_texts.') === 0) {
            return trans($key, $replace);
        }

        $key = strpos($key, 'messages.') === 0?substr($key,9):$key;
        $local = app()->getLocale();
        try {
            $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
            $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));

            if (!array_key_exists($key, $lang_array)) {
                $lang_array[$key] = $processed_key;
                $str = "<?php return " . var_export($lang_array, true) . ";";
                file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
                $result = $processed_key;
            } else {
                $result = trans('messages.' . $key, $replace);
            }
        } catch (\Exception $exception) {
            info($exception->getMessage());
            $result = trans('messages.' . $key, $replace);
        }

        return $result;
    }
}

if (! function_exists('order_place')) {

    function order_place($data) {
        $order = Order::find($data->attribute_id);
        $order->order_status='confirmed';
        if($order->payment_method != 'partial_payment'){
            $order->payment_method=$data->payment_method;
        }
        // $order->transaction_reference=$data->transaction_ref;
        $order->payment_status='paid';
        $order->confirmed=now();
        $order->save();

        if( $order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub)){
            if ($order->restaurant->restaurant_sub->max_order != "unlimited" && $order->restaurant->restaurant_sub->max_order > 0 ) {
                $order->restaurant->restaurant_sub()->decrement('max_order' , 1);
            }
        }

        OrderLogic::update_unpaid_order_payment(order_id:$order->id, payment_method:$data->payment_method);
        //PlaceOrderMail
        try {
            Helpers::send_order_notification($order);


            $address = json_decode($order->delivery_address, true);

            $order_verification_mail_status = Helpers::get_mail_status('order_verification_mail_status_user');
            if ( config('order_delivery_verification') == 1 && $order_verification_mail_status == '1' && $order->is_guest == 0) {
                Mail::to($order->customer->email)->send(new OrderVerificationMail($order->otp,$order->customer->f_name));
            }

            if ($order->is_guest == 1 && config('mail.status') && $order_verification_mail_status == '1' && isset($address['contact_person_email'])) {
                Mail::to($address['contact_person_email'])->send(new OrderVerificationMail($order->otp,$order->customer->f_name));
            }


        } catch (\Exception $e) {
             info($e);
        }
    }
}
    if (! function_exists('order_failed')) {
        function order_failed($data) {
            $order = Order::find($data->attribute_id);
            $order->order_status='failed';
            if($order->payment_method != 'partial_payment'){
                $order->payment_method=$data->payment_method;
            }
            $order->failed=now();
            $order->save();
        }
    }

    if (! function_exists('wallet_success')) {
        function wallet_success($data) {
            $order = WalletPayment::find($data->attribute_id);
            $order->payment_method=$data->payment_method;
            // $order->transaction_reference=$data->transaction_ref;
            $order->payment_status='success';
            $order->save();
            $wallet_transaction = CustomerLogic::create_wallet_transaction($data->payer_id, $data->payment_amount, 'add_fund',$data->payment_method);
            if($wallet_transaction)
            {
                $mail_status = Helpers::get_mail_status('add_fund_mail_status_user');
                try{
                    if(config('mail.status') && $mail_status == '1') {
                        Mail::to($wallet_transaction->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
                    }
                }catch(\Exception $ex)
                {
                    info($ex->getMessage());
                }
            }
        }
    }
    if (! function_exists('wallet_failed')) {
        function wallet_failed($data) {
            $order = WalletPayment::find($data->attribute_id);
            $order->payment_status='failed';
            $order->payment_method=$data->payment_method;
            $order->save();
        }
    }

    if (! function_exists('sub_success')) {
        function sub_success($data){
            $subscription_transaction= SubscriptionTransaction::where('id',$data->attribute_id)->with('restaurant','restaurant.restaurant_sub_update_application')->first();
            $subscription_transaction->payment_status ='success';
            $subscription_transaction->reference = $data->transaction_id;
            $subscription_transaction->payment_method = $data->payment_method;
            $subscription_transaction->transaction_status = 1;
            $subscription_transaction->restaurant->restaurant_sub_update_application->update([
                // 'expiry_date'=> Carbon::now()->addDays($subscription_transaction->validity)->format('Y-m-d'),
                'status'=>1
            ]);
            $subscription_transaction->save();
        }
    }

    if (! function_exists('sub_fail')) {
        function sub_fail($data){
            $subscription_transaction= SubscriptionTransaction::where('id',$data->attribute_id)->with('restaurant')->first();
            $subscription_transaction->payment_status ='failed';
            $subscription_transaction->reference = $data?->transaction_id ?? null;
            $subscription_transaction->payment_method = $data->payment_method;
            $subscription_transaction->save();
        }
    }


    if (! function_exists('collect_cash_fail')) {
        function collect_cash_fail($data){
            return 0;
        }
    }
    if (! function_exists('collect_cash_success')) {
        function collect_cash_success($data){

            try {
            $account_transaction = new AccountTransaction();
            if($data->attribute === 'restaurant_collect_cash_payments'){
                $restaurant = Restaurant::where('vendor_id', $data->attribute_id)->first();
                $restaurant->status = 1;
                $restaurant->save();
                $user_data = $restaurant?->vendor;
                $current_balance = $user_data?->wallet?->collected_cash ?? 0;
                $account_transaction->from_type = 'restaurant';
                $account_transaction->from_id = $restaurant?->vendor?->id;
                $account_transaction->created_by = 'restaurant';
            }
            elseif($data->attribute === 'deliveryman_collect_cash_payments'){
                $user_data = DeliveryMan::findOrFail($data->attribute_id);
                $user_data->status = 1;
                $user_data->save();
                $current_balance = $user_data?->wallet?->collected_cash ?? 0;
                $account_transaction->from_type = 'deliveryman';
                $account_transaction->from_id = $user_data->id;
                $account_transaction->created_by = 'deliveryman';
            }
            else{
                return 0;
            }
            $account_transaction->method = $data->payment_method;
            $account_transaction->ref = $data->attribute;
            $account_transaction->amount = $data->payment_amount;
            $account_transaction->current_balance = $current_balance;

            DB::beginTransaction();
            $account_transaction->save();
            $user_data?->wallet?->decrement('collected_cash', $account_transaction->amount);
            AdminWallet::where('admin_id', Admin::where('role_id', 1)->first()->id)->increment('digital_received',  $account_transaction->amount );

            DB::commit();


            } catch (\Exception $exception) {
                info($exception->getMessage());
                DB::rollBack();

            }


                try {
                    if($data->attribute == 'deliveryman_collect_cash_payments' && config('mail.status') && Helpers::get_mail_status('cash_collect_mail_status_dm') == 1 ){
                        Mail::to($user_data['email'])->send(new \App\Mail\CollectCashMail($account_transaction,$user_data['f_name']));
                    }
                } catch (\Exception $exception) {
                    info($exception->getMessage());
                }
            return true;
        }
    }

    if (!function_exists('addon_published_status')) {
        function addon_published_status($module_name)
        {
            $is_published = 0;
            try {
                $full_data = include("Modules/{$module_name}/Addon/info.php");
                $is_published = $full_data['is_published'] == 1 ? 1 : 0;
                return $is_published;
            } catch (\Exception $exception) {
                info($exception->getMessage());
                return 0;
            }
        }
    }

if (!function_exists('dynamicAsset')) {
    function dynamicAsset(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('public/', '', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}
if (!function_exists('dynamicStorage')) {
    function dynamicStorage(string $directory): string
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $result = str_replace('storage/app/public', 'storage', $directory);
        } else {
            $result = $directory;
        }
        return asset($result);
    }
}
