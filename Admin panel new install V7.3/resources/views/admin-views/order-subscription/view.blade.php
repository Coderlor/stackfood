@extends('layouts.admin.app')

@section('title',translate('messages.subscription_preview'))

@section('content')
@php
    $reasons=\App\Models\OrderCancelReason::where('status', 1)->where('user_type' ,'admin' )->get();
@endphp
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{route('admin.order.subscription.index')}}">
                                    {{translate('messages.subscription_order') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{translate('messages.subscription_order_preview')}}</li>
                        </ol>
                    </nav>
                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{translate('messages.subscription_order_id_#')}}
                            <a href="{{route('admin.order.details',['id'=>$subscription->order->id])}}">{{$subscription->order->id}}</a></h1>
                        <span class="badge badge-primary ml-sm-3 p-1">
                            {{ translate('messages.'.$subscription->type) }}
                        </span>
                        <span class="ml-2 ml-sm-3">
                            <i class="tio-date-range">
                            </i> {{translate('messages.subscription_period')}} : <strong>
                                {{  \App\CentralLogics\Helpers::date_format($subscription->start_at) }}
                                            -
                                {{  \App\CentralLogics\Helpers::date_format($subscription->end_at) }}
                            </strong>
                        </span>
                        @if (in_array($subscription->status, ['paused', 'canceled']))
                            <span class="badge badge-{{$subscription->status=='canceled'?'danger':'warning'}} ml-sm-3 p-1">
                                {{ translate('messages.'.$subscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header mb-3 border-bottom">
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='info'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}">{{translate('messages.subscription_order_info') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='delivery-log'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=delivery-log"  aria-disabled="true">{{translate('messages.delivery_log')}}
                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_completed_subscription_deliveries_of_this_order_ID.')}}" class="input-label-secondary"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='pause-log'?'active':''}}" href="{{route('admin.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=pause-log"  aria-disabled="true">{{translate('messages.pause_log')}}  <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_paused_subscription_deliveries_of_this_order_ID_and_who_paused_it.')}}" class="input-label-secondary"><img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-8 mb-3 mb-lg-0">
                @include("admin-views.order-subscription.partials._{$tab}")
            </div>

            <div class="col-lg-4">
                <div class="card mb-2">
                    <!-- Header -->
                    <div class="card-header border-0 justify-content-center pt-4 pb-0">
                        <h4 class="card-header-title">{{translate('messages.subscription_setup')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Static -->
                        <label class="form-label">{{translate('change_subscription_status')}}</label>
                        <!-- Unfold -->
                        <div>
                            <div class="dropdown">
                                <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{translate("messages.{$subscription->status}")}}</button>
                                <div class="dropdown-menu text-capitalize w-100" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item {{$subscription->status=='canceled'?'update-subscription-status':''}} {{$subscription->status=='active' ? 'active' : ''}}" type="button" @if($subscription->status=='canceled') data-status="active" @else disabled @endif>{{translate('messages.Active')}}</button>
                                    <button class="dropdown-item {{$subscription->status=='active'?'update-subscription-status':''}} {{$subscription->status=='canceled' ? 'active' : ''}}" type="button" @if($subscription->status=='active') data-status="canceled" @else disabled @endif>{{translate('messages.cancel')}}</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Unfold -->
                        @if ($subscription->status != 'expired')
                        <button class="btn btn-sm btn-outline-danger w-100 text-capitalize mt-3 {{$subscription->status != 'canceled'?'update-subscription-status':''}}" type="button" @if($subscription->status != 'canceled') data-status="paused" @else disabled @endif>{{translate('messages.add_new_pause_log')}}</button>
                        @endif
                        <!-- Static -->
                    </div>

                    <!-- End Body -->
                </div>
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{translate('messages.customer')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($subscription->customer)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img onerror-image"
                                        data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                                        src="{{\App\CentralLogics\Helpers::onerror_image_helper($subscription->customer->image, dynamicStorage('storage/app/public/profile/').'/'.$subscription->customer->image, dynamicAsset('public/assets/admin/img/160x160/img1.jpg'), 'profile/') }}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <a class="text-body text-capitalize" href="{{route('admin.customer.view',[$subscription['user_id']])}}">{{$subscription->customer['f_name'].' '.$subscription->customer['l_name']}}</a>
                                </div>
                                <div class="media-body text-right">
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary">{{$subscription->customer->order_count}} {{translate('messages.orders')}}</span>
                                </div>
                                <div class="media-body text-right">
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{translate('messages.contact_info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    {{$subscription->customer['email']}}
                                </li>
                                <li>
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    {{$subscription->customer['phone']}}
                                </li>
                            </ul>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{translate('messages.addresses')}}</h5>
                            </div>

                            @foreach($subscription->customer->addresses as $address)
                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>
                                        <i class="tio-tab mr-2"></i>
                                        {{$address['address_type']}}
                                    </li>
                                    <li>
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        {{$address['contact_person_number']}}
                                    </li>
                                    <li class="cursor-pointer">
                                        <a target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}">
                                            <i class="tio-map mr-2"></i>
                                            {{$address['address']}}
                                        </a>
                                    </li>
                                </ul>
                                <hr>
                            @endforeach

                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->


            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')

    <script>
        "use strict";
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('.update-subscription-status').on('click',function (){
            let status = $(this).data('status');
            update_subscription_status(status);
        })
        function update_subscription_status(status)
        {
            if(status == 'paused'){
                Swal.fire( {
                    title: "{{translate('messages.please_select_a_date_range')}}",
                    html:'<input type="text" placeholder="{{ translate('Select_date')}}" id="swal-input2" class="swal2-input form-control text-center" readonly required/>',
                    confirmButtonText: "{{translate('messages.Submit')}}",
                    onOpen: function() {
                        $('#swal-input2').daterangepicker({
                            minDate: new Date(),
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'

                            }
                        });
                        $('.daterangepicker').css('z-index', 9999);
                        $('#swal-input2').on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
                        });

                        $('#swal-input2').on('cancel.daterangepicker', function(ev, picker) {
                            $(this).val('');
                        });
                    },
                    preConfirm: () => {
                        if((document.getElementById('swal-input2').value == "") || (document.getElementById('swal-input2').value == '') || ((document.getElementById('swal-input2').value == null)) ){
                            Swal.showValidationMessage(`{{translate('messages.please_select_a_date_range')}}`)
                        }
                    }
                }).then((result) => {
                    if(result.value){
                        let startDate = $('#swal-input2').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        let endDate = $('#swal-input2').data('daterangepicker').endDate.format('YYYY-MM-DD')
                        $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="` + status + `" >
                        <input type="hidden" name="start_date" value="` + startDate + `" >
                        <input type="hidden" name="end_date" value="` + endDate + `" >
                        </form>`).appendTo('body').submit();
                    }
                });
            }
            else if(status == 'canceled'){
                Swal.fire( {
                    title: "{{translate('messages.please_select_reason_for_cancellation')}}",
                    html:`
                    <select class="form-control js-select2-custom mx-1 swal2-input"  name="reason" id="reason">
                    <option value="">
                            {{  translate('select_cancellation_reason') }}
                        </option>
                    @foreach ($reasons as $r)
                        <option value="{{ $r->reason }}">
                            {{ $r->reason }}
                        </option>
                    @endforeach
                    </select>
                    <textarea name="note" id="note" class="swal2-input form-control  text-center" placeholder="{{ translate('Add_a_note') }}"></textarea>
                    `,
                    confirmButtonText: "{{translate('messages.Submit')}}",

                    preConfirm: () => {
                        if(document.getElementById('reason').value == "" ){
                            Swal.showValidationMessage(`{{translate('messages.please_select_a_cencellation_reason')}}`)
                        }
                    }
                }).then((result) => {
                    console.log(result, result.value);
                    if (result.value) {

                        $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="` + status + `" >
                        <input type="hidden" name="reason" value="` + document.getElementById('reason').value + `" >
                        <input type="hidden" name="note" value="` + document.getElementById('note').value + `" >
                        </form>`).appendTo('body').submit();
                    }
                })

            }

            else {
                Swal.fire({
                    title: "{{translate('messages.are_you_sure?')}}",
                    text: status=='active' ? "{{translate('you_want_to_active_this_subscription_?')}}" : "{{translate('you_want_to_cancel_this_subscription_?')}}" ,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{translate('messages.no')}}',
                    confirmButtonText: '{{translate('messages.Yes')}}',
                    reverseButtons: true
                }).then((result) => {
                    console.log(result, result.value);
                    if (result.value) {
                        $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="` + status + `" >
                        </form>`).appendTo('body').submit();
                    }
                })
            }

        }
    </script>
@endpush
