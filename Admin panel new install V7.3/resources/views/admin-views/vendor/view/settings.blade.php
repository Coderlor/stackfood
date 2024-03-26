@php use App\Models\BusinessSetting; @endphp
@extends('layouts.admin.app')
@section('title',$restaurant->name."'s".translate('messages.settings'))
@section('content')
    @php($business_model = BusinessSetting::where('key', 'business_model')->first())
    @php($order_subscription = BusinessSetting::where('key', 'order_subscription')->first())

    @php($business_model = isset($business_model->value) ? json_decode($business_model->value, true) : [
        'commission'        =>  1,
        'subscription'     =>  0,
    ])
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
                </h1>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

                <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>
                @include('admin-views.vendor.view.partials._header',['restaurant'=>$restaurant])
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon"><i class="tio-fastfood"></i></span> &nbsp;
                    <span>{{translate('messages.restaurant_settings')}}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 form-control"
                                for="food_section">
                            <span class="pr-2 d-flex">
                                <span>{{translate('messages.Food_Management')}}</span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title='{{translate("When_disabled,_the_food_management_feature_will_be_hidden_from_the_restaurant_panel_&_restaurant_app.")}}'
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                <input type="checkbox"
                                       data-id="food_section"
                                       data-type="status"
                                       data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/veg-on.png') }}"
                                       data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/veg-off.png') }}"
                                       data-title-on="{{ translate('Want_to_enable_Food_Management_for_this_restaurant?') }}"
                                       data-title-off="{{ translate('Want_to_disable_Food_Management_for_this_restaurant?') }}"
                                       data-text-on="<p>{{ translate('If_enabled,_the_food_management_feature_will_be_available_for_this_restaurant.') }}</p>"
                                       data-text-off="<p>{{ translate('If_disabled,_the_food_management_feature_will_be_hidden_from_this_restaurant.') }}</p>"
                                       class="toggle-switch-input dynamic-checkbox"
                                       name="food_section" id="food_section" {{$restaurant->food_section?'checked':''}}>
                                <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                            </label>
                            <form
                                action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->food_section?0:1, 'food_section'])}}"
                                method="get" id="food_section_form">
                            </form>
                        </div>
                    </div>


                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                for="schedule_order">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.scheduled_delivery')}}
                                </span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title="{{translate('When_enabled,_restaurant_owners_can_take_scheduled_orders_from_customers')}}"
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                <input type="checkbox"
                                       data-id="schedule_order"
                                       data-type="status"
                                       data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-on.png') }}"
                                       data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-off.png') }}"
                                       data-title-on="{{ translate('Want_to_enable_Schedule_Order_for_this_restaurant?') }}"
                                       data-title-off="{{ translate('Want_to_disable_Schedule_Order_for_this_restaurant?') }}"
                                       data-text-on="<p>{{ translate('If_enabled,_the_scheduled_order_option_will_be_available_for_this_restaurant’s_products.') }}</p>"
                                       data-text-off="<p>{{ translate('If_disabled,_the_scheduled_order_option_will_be_hidden_for_this_restaurant’s_products.') }}</p>"
                                       class="toggle-switch-input dynamic-checkbox"


                                       id="schedule_order" {{$restaurant->schedule_order?'checked':''}}>
                                <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                            </label>
                            <form
                                action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->schedule_order?0:1, 'schedule_order'])}}"
                                method="get" id="schedule_order_form">
                            </form>
                        </div>
                    </div>
                    @if ($restaurant->restaurant_model == 'commission')
                        <div class="col-xl-4 col-md-4 col-sm-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                    for="reviews_section">
                        <span class="pr-2 d-flex">
                            <span class="line--limit-1">
                                {{translate('messages.Reviews_section')}}
                            </span>
                            <span data-toggle="tooltip" data-placement="right"
                                  data-original-title="{{translate('When_enabled,_restaurant_owners_can_see_customer’s_review.')}}"
                                  class="input-label-secondary">
                                <i class="tio-info-outined"></i>
                            </span>
                        </span>
                                    <input type="checkbox"
                                           data-id="reviews_section"
                                           data-type="status"
                                           data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/this-criteria-on.png') }}"
                                           data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/this-criteria-off.png') }}"
                                           data-title-on="{{ translate('Want_to_enable_reviews_section_for_this_restaurant?') }}"
                                           data-title-off="{{ translate('Want_to_disable_reviews_section_for_this_restaurant?') }}"
                                           data-text-on="<p>{{ translate('If_enabled,_restaurant_owners_can_see_customer’s_review.') }}</p>"
                                           data-text-off="<p>{{ translate('If_disabled,_restaurant_owners_can_not_see_customer’s_review.') }}</p>"
                                           class="toggle-switch-input dynamic-checkbox"
                                           name="reviews_section"
                                           id="reviews_section" {{$restaurant->reviews_section?'checked':''}}>
                                    <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                                </label>
                                <form
                                    action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->reviews_section?0:1, 'reviews_section'])}}"
                                    method="get" id="reviews_section_form">
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                    for="pos_system">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.POS_Section')}}
                                </span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title="{{translate('If this option is turned on, the restaurant panel will get the Point of Sale (POS) option.')}}"
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                    <input type="checkbox"
                                           data-id="pos_system"
                                           data-type="status"
                                           data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/criteria-on.png') }}"
                                           data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/criteria-off.png') }}"
                                           data-title-on="{{ translate('Want_to_enable_pos_system_for_this_restaurant?') }}"
                                           data-title-off="{{ translate('Want_to_disable_pos_system_for_this_restaurant?') }}"
                                           data-text-on="<p>{{ translate('If_enabled,_restaurant_owners_use_the_pos_system.') }}</p>"
                                           data-text-off="<p>{{ translate('If_disabled,_pos_system_will_be_hidden_for_this_restaurant.') }}</p>"
                                           class="toggle-switch-input dynamic-checkbox"
                                           id="pos_system" {{$restaurant->pos_system?'checked':''}}>
                                    <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                                </label>
                                <form
                                    action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->pos_system?0:1, 'pos_system'])}}"
                                    method="get" id="pos_system_form">
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                    for="self_delivery_system">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.self_delivery')}}
                                </span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title="{{translate('When_this_option_is_enabled,_restaurants_need_to_deliver_orders_by_themselves_or_by_their_own_delivery_man._Restaurants_will_also_get_an_option_for_adding_their_own_delivery_man_from_the_restaurant_panel.')}}"
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                    <input type="checkbox"
                                           data-id="self_delivery_system"
                                           data-type="status"
                                           data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/home-delivery-on.png') }}"
                                           data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/home-delivery-off.png') }}"
                                           data-title-on="{{ translate('Want_to_enable_self_delivery_system_for_this_restaurant?') }}"
                                           data-title-off="{{ translate('Want_to_disable_self_delivery_system_for_this_restaurant?') }}"
                                           data-text-on="<p>{{ translate('If_enabled,_restaurant_owners_can_use_their_own_delivery_system.') }}</p>"
                                           data-text-off="<p>{{ translate('If_disabled,_self_delivery_option_will_be_hidden_for_this_restaurant.') }}</p>"
                                           class="toggle-switch-input dynamic-checkbox"

                                           id="self_delivery_system" {{$restaurant->self_delivery_system?'checked':''}}>
                                    <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                                </label>
                                <form
                                    action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->self_delivery_system?0:1, 'self_delivery_system'])}}"
                                    method="get" id="self_delivery_system_form">
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                for="delivery">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.home_delivery')}}
                                </span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title="{{translate('When_enabled,_customers_can_make_home_delivery_orders_from_this_restaurant.')}}"
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                <input type="checkbox" name="delivery"
                                data-id="delivery"
                                data-type="status"
                                data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/dm-self-reg-on.png') }}"
                                data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/dm-self-reg-off.png') }}"
                                data-title-on="{{ translate('Want_to_enable_Home_Delivery_for_this_restaurant?') }}"
                                data-title-off="{{ translate('Want_to_disable_Home_Delivery_for_this_restaurant?') }}"
                                data-text-on="<p>{{ translate('If_enabled,_the_home_delivery_feature_will_be_available_for_the_restaurant’s_items.') }}</p>"
                                data-text-off="<p>{{ translate('If_disabled,_the_home_delivery_feature_will_be_hidden_from_this_restaurant’s_items.') }}</p>"
                                class="toggle-switch-input dynamic-checkbox"
                                       id="delivery" {{$restaurant->delivery?'checked':''}}>
                                <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                            </label>
                            <form
                                action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->delivery?0:1, 'delivery'])}}"
                                method="get" id="delivery_form">
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                for="take_away">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.Takeaway')}}
                                </span>
                                <span data-toggle="tooltip" data-placement="right"
                                      data-original-title='{{translate("When_enabled,_customers_can_place_takeaway/self-pickup_orders_from_this_restaurant.")}}'
                                      class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                                <input type="checkbox"
                                       data-id="take_away"
                                        data-type="status"
                                        data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/takeaway-on.png') }}"
                                        data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/takeaway-off.png') }}"
                                        data-title-on="{{ translate('Want_to_enable_take_away_for_this_restaurant?') }}"
                                        data-title-off="{{ translate('Want_to_disable_take_away_for_this_restaurant?') }}"
                                        data-text-on="<p>{{ translate('If_enabled,_the_takeaway_feature_will_be_available_for_the_restaurant.') }}</p>"
                                        data-text-off="<p>{{ translate('If_disabled,_the_takeaway_feature_will_be_hidden_from_the_restaurant.') }}</p>"
                                        class="toggle-switch-input dynamic-checkbox"
                                       id="take_away" {{$restaurant->take_away?'checked':''}}>
                                <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                            </label>
                            <form
                                action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->take_away?0:1, 'take_away'])}}"
                                method="get" id="take_away_form">
                            </form>
                        </div>
                    </div>

                    @if (isset($order_subscription) && $order_subscription->value == 1)
                        <div class="col-xl-4 col-md-4 col-sm-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                    for="order_subscription">
                                <span class="pr-2 d-flex">
                                    <span class="line--limit-1">
                                        {{translate('messages.order_subscription')}}
                                    </span>
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("If this option is on , customer can place subscription based order in user app.")}}'
                                          class="input-label-secondary">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                    <input type="checkbox"
                                    data-id="order_subscription"
                                    data-type="status"
                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/store-reg-on.png') }}"
                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/store-reg-off.png') }}"
                                    data-title-on="{{ translate('Want_to_enable_order_subscription_for_this_restaurant?') }}"
                                    data-title-off="{{ translate('Want_to_disable_order_subscription_for_this_restaurant?') }}"
                                    data-text-on="<p>{{ translate('If_enabled,_the_order_subscription_feature_will_be_available_for_the_restaurant.') }}</p>"
                                    data-text-off="<p>{{ translate('If_disabled,_the_order_subscription_feature_will_be_hidden_from_the_restaurant.') }}</p>"
                                    class="toggle-switch-input dynamic-checkbox"
                                           id="order_subscription" {{$restaurant->order_subscription_active == 1?'checked':''}}>

                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                                </label>
                                <form
                                    action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->order_subscription_active?0:1, 'order_subscription_active'])}}"
                                    method="get" id="order_subscription_form">
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                for="instant_order">
                                <span class="pr-2 d-flex">
                                    <span class="line--limit-1">
                                        {{translate('messages.instant_order')}}
                                    </span>
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("If_enabled,_customers_can_instantly_order_from_this_restaurant._Otherwise,_customers_can_only_place_“scheduled_orders”.")}}'
                                          class="input-label-secondary">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                <input type="checkbox"
                                data-id="instant_order"
                                data-type="status"
                                data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/veg-on.png') }}"
                                data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/veg-off.png') }}"
                                data-title-on="{{ translate('Want_to_enable_instant_order_for_this_restaurant?') }}"
                                data-title-off="{{ translate('Want_to_disable_instant_order_for_this_restaurant?') }}"
                                data-text-on="<p>{{ translate('If_enabled,_customers_can_order_instantly.') }}</p>"
                                data-text-off="<p>{{ translate('If_disabled,_customers_can_not_order_instantly.') }}</p>"
                                class="toggle-switch-input dynamic-checkbox"
                                       id="instant_order" {{$restaurant->restaurant_config?->instant_order == 1?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                            <form
                                action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->restaurant_config?->instant_order?0:1, 'instant_order'])}}"
                                method="get" id="instant_order_form">
                            </form>
                        </div>
                    </div>

                    @php($self_delivey =0)
                    @if (($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system == 1) )
                        @php($self_delivey =1)
                    @endif

                    @if ($self_delivey  == 1 )
                        <div class="col-xl-4 col-md-4 col-sm-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control"
                                    for="customer_date_order_sratus">
                                <span class="pr-2 d-flex">
                                    <span class="line--limit-1">
                                        {{translate('messages.custom_date_order_status')}}
                                    </span>
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("If_enabled,_customers_can_choose_a_custom_date_during_scheduled_order_placement.")}}'
                                          class="input-label-secondary">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                    <input type="checkbox"
                                    data-id="customer_date_order_sratus"
                                    data-type="status"
                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-on.png') }}"
                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-off.png') }}"
                                    data-title-on="{{ translate('Want_to_enable_customer_date_order_sratus_for_this_restaurant?') }}"
                                    data-title-off="{{ translate('Want_to_disable_customer_date_order_sratus_for_this_restaurant?') }}"
                                    data-text-on="<p>{{ translate('If_enabled,_customers_can_not_select_schedule_date_over_the_given_days._and_you_must_set_a_date_on_the') }} <b>{{ translate('Customer_Can_Order_Within_field') }}</b></p>"
                                    data-text-off="<p>{{ translate('If_disabled,_customers_can_select_any_schedule_date.') }}</p>"
                                    class="toggle-switch-input dynamic-checkbox"
                                           id="customer_date_order_sratus" {{$restaurant->restaurant_config?->customer_date_order_sratus == 1?'checked':''}}>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                                </label>
                                <form
                                    action="{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->restaurant_config?->customer_date_order_sratus?0:1, 'customer_date_order_sratus'])}}"
                                    method="get" id="customer_date_order_sratus_form">
                                </form>
                            </div>
                        </div>
                    @endif


                </div>

                <form action="{{route('admin.restaurant.update-settings',[$restaurant['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2 mt-4">

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="input-label text-capitalize">{{ translate('Restaurant_Type') }}

                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Set_the_food_type_(veg/nonveg/both)_this_restaurant_can_sell.")}}'
                                          class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                                </label>
                                @php($restaurant_type = \App\Models\Restaurant::where(['id'=>$restaurant->id])->select('veg','non_veg')->first())
                                <div class="resturant-type-group border">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        @php($checked = ($restaurant_type->veg == 1 && $restaurant_type->non_veg == 0) ? 'checked' : '')
                                        <input class="form-check-input" type="radio" name="menu" id="check-veg"
                                               {{$checked}} value="veg">
                                        <span class="form-check-label">
                                        {{translate('messages.veg')}}
                                    </span>
                                    </label>
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        @php($checked = ($restaurant_type->veg == 0 && $restaurant_type->non_veg == 1) ? 'checked' : '')
                                        <input class="form-check-input" type="radio" name="menu" id="check-non-veg"
                                               {{$checked}} value="non-veg">
                                        <span class="form-check-label">
                                        {{translate('messages.non_veg')}}
                                    </span>
                                    </label>
                                    <label class="form-check form--check">
                                        @php($checked = ($restaurant_type->veg == 1 && $restaurant_type->non_veg == 1) ? 'checked' : '')
                                        <input class="form-check-input" type="radio" name="menu" id="check-both"
                                               {{$checked}} value="both">
                                        <span class="form-check-label">
                                        {{translate('messages.both')}}
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label text-capitalize"
                                       for="minimum_order">{{translate('messages.minimum_order_amount')}}

                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Specify_the_minimum_order_amount_required_for_customers_when_ordering_from_this_restaurant.")}}'
                                          class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                                </label>
                                <input type="number" id="minimum_order" name="minimum_order" step="0.01" min="0" max="100000"
                                       class="form-control" placeholder="{{ translate('messages.Ex:_100') }} "
                                       value="{{$restaurant->minimum_order??'0'}}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label id="tax" class="text-dark d-block">
                                    <span>{{translate('messages.vat/tax')}}(%)</span>
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Specify_the_vat/tax_required_for_customers_when_ordering_from_this_restaurant.")}}'
                                          class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                                </label>
                                <input type="number" id="tax" min="0" max="10000" step="0.01" name="tax"
                                       class="form-control" placeholder="{{ translate('messages.Ex:_100') }} " required
                                       value="{{$restaurant->tax??'0'}}" {{isset($restaurant->tax)?'':'readonly'}}>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="input-label text-capitalize"
                                   for="minimum_delivery_time">{{translate('messages.approx_delivery_time')}}<span
                                    class="input-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{translate('Set_the_maximum_time_required_to_deliver_an_order.')}}"><img
                                        src="{{dynamicAsset('/public/assets/admin/img/info-circle.svg')}}"
                                        alt="{{translate('Set_the_maximum_time_required_to_deliver_an_order.')}}"></span></label>
                            <div class="input-group">
                                <input id="minimum_delivery_time" type="number" name="minimum_delivery_time" class="form-control"
                                       placeholder="Min: 10" value="{{explode('-',$restaurant->delivery_time)[0]}}"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="{{translate('messages.minimum_delivery_time')}}">
                                <input type="number" name="maximum_delivery_time" class="form-control"
                                       placeholder="Max: 20" value="{{explode('-',$restaurant->delivery_time)[1]}}"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="{{translate('messages.maximum_delivery_time')}}">
                                <select name="delivery_time_type" class="form-control text-capitalize" id="" required>
                                    @php($data= explode('-',$restaurant->delivery_time)[2] ??  null )
                                    <option
                                        value="min" {{$data == 'min' ?'selected':''}}>{{translate('messages.minutes')}}</option>
                                    <option
                                        value="hours" {{$data == 'hours' ?'selected':''}}>{{translate('messages.hours')}}</option>
                                </select>
                            </div>
                        </div>
                        @if ($restaurant->restaurant_model == 'commission')
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label
                                        class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1"
                                        for="comission_status">
                                <span class="form-check-label">
                                    {{translate('messages.admin_commission')}}(%)
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Specify_the_commission_when_ordering_from_this_restaurant.")}}'
                                          class="input-label-secondary">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                        <input type="checkbox" class="toggle-switch-input"
                                               name="comission_status" id="comission_status"
                                               value="1" {{isset($restaurant->comission)?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                                    </label>
                                    <input type="number" id="comission" min="0" max="10000" step="0.01" name="comission"
                                           class="form-control" required
                                           value="{{$restaurant->comission??'0'}}" {{isset($restaurant->comission)?'':'readonly'}}>
                                </div>
                            </div>
                        @endif

                        @if ($self_delivey == 1 )
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label text-capitalize"
                                           for="customer_order_date">{{ translate('Customer_Can_Order_Within') }}
                                        ({{ translate('messages.Days') }})
                                        <span data-toggle="tooltip" data-placement="right"
                                              data-original-title="{{translate('Enter_the_number_of_days_customers_can_select_for_scheduled_orders.')}}"
                                              class="input-label-secondary"><img
                                                src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}"
                                                alt="i"></span>
                                    </label>
                                    <input type="number" name="customer_order_date" id="customer_order_date"
                                           {{ $restaurant?->restaurant_config?->customer_date_order_sratus == 1 ? 'required' :'readonly' }} min="0"
                                           max="99999999" class="form-control" placeholder="30"
                                           value="{{ $restaurant?->restaurant_config?->customer_order_date ?? '' }}">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn--primary">{{translate('messages.save_changes')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <form action="{{route('admin.restaurant.update-settings',[$restaurant['id']])}}" id="Business_Model_change"
              method="post">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-settings"></i>
                </span> &nbsp;
                        <span>{{translate('messages.Restaurant_Business_Model')}}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($business_model['commission'] == 0 &&  $business_model['subscription'] == 1 )
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="inputState">{{  translate('Restaurant_Business_Model') }}
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Choose_the_business_model_for_this_restaurant_(Subscription-based).")}}'
                                          class="input-label-secondary">
                            <i class="tio-info-outined"></i>
                        </span>
                                </label>
                                <select name="restaurant_model" id="inputState" class="form-control">
                                    @if ($restaurant->restaurant_model == 'none')
                                        <option selected > {{ translate('messages.None') }} </option>
                                        <option  value="subscription"  > {{ translate('messages.Subscription') }} </option>
                                    @elseif ($restaurant->restaurant_model == 'unsubscribed')
                                        <option selected > {{ translate('messages.Unsubscribed') }} </option>
                                    @elseif ($restaurant->restaurant_model == 'commission')
                                        <option selected > {{ translate('messages.None') }} </option>
                                        <option value="subscription" > {{ translate('messages.Subscription') }} </option>
                                    @else
                                        <option value="subscription" > {{ translate('messages.Subscription') }} </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    @elseif($business_model['commission'] == 1 &&  $business_model['subscription'] == 1 )
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="inputState">{{  translate('Restaurant_Business_Model') }}
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Choose_the_business_model_for_this_restaurant_(Commission-based_or_Subscription-based).")}}'
                                          class="input-label-secondary">
                            <i class="tio-info-outined"></i>
                        </span>
                                </label>
                                <select name="restaurant_model" id="inputState" class="form-control">
                                    @if ($restaurant->restaurant_model == 'none')
                                        <option selected > {{ translate('messages.None') }} </option>
                                        <option  value="subscription" > {{ translate('messages.Subscription') }} </option>
                                        <option  value="commission" > {{ translate('messages.Commission') }}</option>
                                    @elseif ($restaurant->restaurant_model == 'unsubscribed')
                                        <option selected > {{ translate('messages.Unsubscribed') }} </option>
                                        <option  value="commission" > {{ translate('messages.Commission') }}</option>
                                    @else
                                        <option  value="subscription" {{ $restaurant->restaurant_model == 'subscription' ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                                        <option  value="commission" {{ $restaurant->restaurant_model == 'commission' ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    @elseif($business_model['commission'] == 1 &&  $business_model['subscription'] == 0 )
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="inputState">{{  translate('Restaurant_Business_Model') }}
                                    <span data-toggle="tooltip" data-placement="right"
                                          data-original-title='{{translate("Choose_the_business_model_for_this_restaurant_(Commission-based).")}}'
                                          class="input-label-secondary">
                            <i class="tio-info-outined"></i>
                        </span>
                                </label>
                                <select name="restaurant_model" id="inputState" class="form-control">
                                    @if ($restaurant->restaurant_model == 'none')
                                        <option selected > {{ translate('messages.None') }} </option>
                                        <option value="commission" > {{ translate('messages.Commission') }}</option>
                                    @elseif ($restaurant->restaurant_model == 'unsubscribed')
                                        <option selected > {{ translate('messages.Unsubscribed') }} </option>
                                        <option  value="commission" > {{ translate('messages.Commission') }}</option>
                                    @elseif ($restaurant->restaurant_model == 'subscription')
                                        <option selected > {{ translate('messages.Subscription') }} </option>
                                        <option value="commission"> {{ translate('messages.Commission') }}</option>
                                    @else
                                        <option value="commission" > {{ translate('messages.Commission') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                    @endif

                    <div class="text-right">
                        <button type="button" class="btn btn-primary h--45px form-alert" href="javascript:"
                        data-id="Business_Model_change"
                        data-message="{{ translate('messages.You_want_to_Change_the_Business_Model_for ') }} {{ $restaurant->name }} {{ translate('messages.This_will_expire_the_current_package') }}" >
                            <span class="ml-1">{{ translate('messages.Change_Restaurant_Business_Model') }}</span>
                        </button>

                    </div>
                </div>
    </div>
        </form>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-clock"></i>
                </span> &nbsp;
                <span>{{translate('messages.Schedule_Working_Hours')}}</span>
                <span data-toggle="tooltip" data-placement="right"
                        data-original-title='{{translate("Set_the_daily_opening_and_closing_times_for_this_restaurant.")}}'
                        class="input-label-secondary">
                    <i class="tio-info-outined"></i>
                </span>
            </h5>
        </div>
        <div class="card-body" id="schedule">
            @include('admin-views.vendor.view.partials._schedule', $restaurant)
        </div>
    </div>
    </div>

    <!-- Create schedule modal -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.Create Schedule')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="add-schedule">
                        @csrf
                        <input type="hidden" name="day" id="day_id_input">
                        <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                        <div class="form-group">
                            <label for="start_time" class="col-form-label">{{translate('messages.Start_time')}}
                                :</label>
                            <input id="start_time" type="time" class="form-control" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time" class="col-form-label">{{translate('messages.End_time')}}:</label>
                            <input id="end_time" type="time" class="form-control" name="end_time" required>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn--primary">{{translate('messages.Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
"use strict";
        $(document).ready(function () {
            $('#dataTable').DataTable();

            $('#exampleModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let day_name = button.data('day');
                let day_id = button.data('dayid');
                let modal = $(this);
                modal.find('.modal-title').text('{{translate('messages.Create_Schedule_For_')}} ' + day_name);
                modal.find('.modal-body input[name=day]').val(day_id);
            })
        });

        $(document).on('ready', function () {
            $("#comission_status").on('change', function () {
                if ($("#comission_status").is(':checked')) {
                    $('#comission').removeAttr('readonly');
                } else {
                    $('#comission').attr('readonly', true).val('0');
                }
            });

        });


        $(document).on('click', '.delete-schedule', function () {
            let route = $(this).data('url');
            Swal.fire({
                title: '{{translate('messages.Want_to_delete_this_schedule_?')}}',
                text: '{{translate('messages.If_you_select_Yes,_the_time_schedule_will_be_deleted')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success('{{translate('messages.Schedule_removed_successfully')}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        },
                        error: function () {
                            toastr.error('{{translate('messages.Schedule_not_found')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                }
            })
        });

        $('#add-schedule').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.restaurant.add-schedule')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{translate('messages.Schedule_added_successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function (XMLHttpRequest) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
