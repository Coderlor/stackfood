@extends('layouts.admin.app')

@section('title', translate('messages.customer_settings'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ dynamicAsset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
                <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                    <div class="blinkings active">
                        <i class="tio-info-outined"></i>
                        <div class="business-notes">
                            <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                            <div>
                                {{translate('Don’t_forget_to_click_the_respective_‘Save_Information’_buttons_below_to_save_changes')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')

        </div>

        <form action="{{ route('admin.customer.update-settings') }}" method="post" enctype="multipart/form-data"
            id="update-settings">
            @csrf
            <div class="row gx-2">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header card-header-shadow">
                            <h5 class="card-title d-flex align-items-center">
                                <img src="{{dynamicAsset('/public/assets/admin/img/wallet-icon.png')}}" alt="" class="card-header-icon align-self-center mr-1">
                                <span>
                                    {{translate('Wallet')}}
                                </span>
                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.If_enabled,_customers_can_have_virtual_wallets_in_their_accounts._They_can_also_earn_(via_referral,_refund,_or_loyalty_points)_and_buy_with_the_wallet’s_amount') }}"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}"></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-4 form-control">
                                            <span class="pr-2">{{ translate('Customer Can Earn & Buy From Wallet') }}
                                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('If_enabled,_customers_can_earn_and_buy_from_their_wallets.') }}">
                                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"

                                            data-id="wallet_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/wallet-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/wallet-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Wallet') }}</strong> {{ translate('feature') }}"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Wallet') }}</strong> {{ translate('feature') }}"
                                            data-text-on="<p>{{ translate('If_enabled,_Customers_can_see_&_use_the_Wallet_option_from_their_profile_in_the_Customer_App_&_Website.') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_Wallet_feature_will_be_hidden_from_the_Customer_App_&_Website') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"


                                            name="customer_wallet"
                                            id="wallet_status" value="1" {{ isset($data['wallet_status']) && $data['wallet_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-4 form-control">
                                            <span class="pr-2">{{ translate('messages.refund_to_wallet') }}<span
                                                    class="input-label-secondary"
                                                    data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.If_enabled,_Customers_will_automatically_receive_the_refunded_amount_in_their_wallets._But_if_it’s_disabled,_the_Admin_will_handle_the_Refund_Request_in_his_convenient_transaction_channel.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.show_hide_food_menu') }}"></span></span>
                                            <input type="checkbox"
                                            data-id="refund_to_wallet"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/refund-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/refund-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Refund to Wallet') }}</strong> {{ translate('feature') }}"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Refund to Wallet') }}</strong> {{ translate('feature') }}"
                                            data-text-on="<p>{{ translate('If_enabled,_Customers_will_automatically_receive_the_refunded_amount_in_their_wallets.') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_Admin_will_handle_the_Refund_Request_in_his_convenient_transaction_channel_other_than_the_wallet.') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"


                                            name="refund_to_wallet"
                                                id="refund_to_wallet" value="1"
                                                {{ isset($data['wallet_add_refund']) && $data['wallet_add_refund'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control">
                                            <span class="pr-2">{{ translate('customer_can_add_fund_to_wallet') }}
                                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.With_this_feature,_customers_can_add_fund_to_wallet_if_the_payment_module_is_available.')}}">
                                              <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.add_fund_status') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"


                                             data-id="add_fund_status"
                                             data-type="toggle"
                                             data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/wallet-on.png') }}"
                                             data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/wallet-off.png') }}"
                                             data-title-on="{{ translate('messages.Want_to_enable') }} <strong>{{ translate('add_fund_to_Wallet_feature?') }}</strong>"
                                             data-title-off="{{ translate('messages.Want_to_disable') }} <strong>{{ translate('add_fund_to_Wallet_feature?') }}</strong>"
                                             data-text-on="<p>{{ translate('messages.If_you_enable_this,_Customers_can_add_fund_to_wallet_using_payment_module') }}</p>"
                                             data-text-off="<p>{{ translate('messages.If_you_disable_this,_add_fund_to_wallet_will_be_hidden_from_the_Customer_App_&_Website.') }}</p>"
                                             class="status toggle-switch-input dynamic-checkbox-toggle"

                                             name="add_fund_status"
                                            id="add_fund_status" value="1"{{ isset($data['add_fund_status']) && $data['add_fund_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header card-header-shadow">
                            <h5 class="card-title d-flex align-items-center">
                                <img src="{{dynamicAsset('/public/assets/admin/img/referral.png')}}" alt="" class="card-header-icon align-self-center mr-1">
                                <span class="pr-2">
                                    {{ translate('Referral_Earning') }}
                                </span>
                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Existing_Customers_can_share_a_referral_code_with_others_to_earn_a_referral_bonus._For_this,_the_new_Customer_MUST_sign_up_using_the_referral_code_and_make_their_first_purchase_successfully') }}">
                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}">
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-4 form-control">
                                            <span
                                                class="pr-2">{{ translate('Customer_Can_Earn_&_Buy_From_Referral') }}</span>
                                            <input type="checkbox"
                                            data-id="ref_earning_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/referral-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/referral-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Referral_Earning') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Referral_Earning') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_Customers_can_earn_points_by_referring_others_to_sign_up_&_first_purchase_successfully_from_your_business.') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_referral-earning_feature_will_be_hidden_from_the_Customer_App_&_Website.') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"
                                                name="ref_earning_status" id="ref_earning_status"
                                                data-section="referrer-earning" value="1"
                                                {{ isset($data['ref_earning_status']) && $data['ref_earning_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="ref_earning_exchange_rate">
                                            {{ translate('Earning_Per_Referral') }} ({{  \App\CentralLogics\Helpers::currency_symbol() }})
                                        </label>
                                            <input type="number" class="form-control" id="ref_earning_exchange_rate" name="ref_earning_exchange_rate" min="0"
                                            value="{{ $data['ref_earning_exchange_rate'] ?? '0' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body py-4">
                            <div class="row g-3 mt-0">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-4 form-control">
                                            <span class="pr-2">{{ translate('Customer_Verification') }}
                                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('If_you_activate_this_feature,_customers_need_to_verify_their_account_information_via_OTP_during_the_signup_process') }}">
                                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="customer_verification_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/customer-verification-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/customer-verification-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Customer_Verification') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Customer_Verification') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_Customers_must_verify_their_account_via_OTP.') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_Customers_don’t_need_to_verify_their_account_via_OTP.') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"

                                            name="customer_verification"
                                            id="customer_verification_status" value="1"  {{ isset($data['customer_verification']) && $data['customer_verification'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header card-header-shadow">
                            <h5 class="card-title">
                                <img src="{{dynamicAsset('/public/assets/admin/img/loyalty.png')}}" alt="" class="card-header-icon align-self-center mr-1">
                                <span>
                                    {{ translate('Loyalty_Point') }}
                                </span>
                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('With_this_feature,_customers_can_earn_loyalty_points_after_purchasing_food_from_this_system.') }}">
                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}">
                                </span>
                            </h5>
                        </div>
                        <div class="card-body py-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-4 form-control"
                                            for="customer_loyalty_point">
                                            <span class="pr-2">{{ translate('Customer_Can_Earn_Loyalty_Point') }}
                                                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('If_enabled,_customers_will_earn_a_certain_amount_of_points_after_each_purchase.') }}">
                                                    <img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.show_hide_food_menu') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="customer_loyalty_point"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/loyalty-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/loyalty-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Loyalty_Point') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Loyalty_Point') }}</strong> ?"
                                            data-text-on="<p>{{ translate('Customer_will_see_loyalty_point_option_in_his_profile_settings_&_can_earn_&_convert_this_point_to_wallet_money') }}</p>"
                                            data-text-off="<p>{{ translate('Customer_will_no_see_loyalty_point_option_from_his_profile_settings') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"
                                                name="customer_loyalty_point"
                                                id="customer_loyalty_point" data-section="loyalty-point-section" value="1"
                                                {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                    <div class="col-md-6 loyalty-point-div {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? '' :'d-none' }}">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="item_purchase_point">
                                                {{ translate('Loyalty_Point_Earn_Per_Order_(%)') }}
                                                <small class="text-danger"><span class="input-label-secondary"
                                                        data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.item_purchase_point_hint') }}"><img
                                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.item_purchase_point_hint') }}"></span> *</small>
                                            </label>
                                            <input type="number" id="item_purchase_point" min="0" class="form-control" name="item_purchase_point" step=".01"
                                                value="{{ $data['loyalty_point_item_purchase_point'] ?? '0' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 loyalty-point-div {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? '' :'d-none' }}">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="minimum_transfer_point">
                                                {{ translate('Minimum_Loyalty_Point_Required_to_Transfer',['currency' => \App\CentralLogics\Helpers::currency_code()]) }}
                                            </label>
                                            <input type="number" id="minimum_transfer_point" min="0" class="form-control" name="minimun_transfer_point"
                                                value="{{ $data['loyalty_point_minimum_point'] ?? '0' }}">
                                        </div>
                                    </div>
                                    <div class="col-12 loyalty-point-div {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? '' :'d-none' }}">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="loyalty_point_exchange_rate">{{ translate('messages.point_to_currency_exchange_rate', ['currency' => \App\CentralLogics\Helpers::currency_code()]) }}</label>
                                            <input type="number" min="0" class="form-control" id="loyalty_point_exchange_rate" name="loyalty_point_exchange_rate"
                                                value="{{ $data['loyalty_point_exchange_rate'] ?? '0' }}">
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset location-reload">{{ translate('reset') }}</button>
                        <button type="submit" id="submit" class="btn btn--primary">{{ translate('Save Information') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

