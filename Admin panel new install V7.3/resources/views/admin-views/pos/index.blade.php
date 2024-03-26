@php use App\CentralLogics\Helpers;
 use App\Models\BusinessSetting;
 use App\Models\Order;
 use App\Models\Restaurant;
 use App\Models\Zone;
 use App\Scopes\RestaurantScope; @endphp
@extends('layouts.admin.app')

@section('title', translate('messages.pos'))
@section('content')

    <div id="pos-div" class="initial-51">
        <section class="section-content padding-y-sm bg-default mt-1">
            <div class="container-fluid content">
                <div class="d-flex flex-wrap">
                    <div class="order--pos-left">
                        <div class="card padding-y-sm card h-100">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title">
                                <span class="card-header-icon">
                                    <i class="tio-fastfood"></i>
                                </span>
                                    <span>
                                    {{translate('food_section')}}
                                </span>
                                </h5>
                            </div>
                            <div class="card-header border-0 pt-4">
                                <div class="w-100">
                                    <div class="row g-3 justify-content-around">
                                        <div class="col-sm-6">
                                            <label for="zone_id"></label>
                                            <select name="zone_id"
                                                    class="form-control js-select2-custom h--45x set-filter"
                                                    data-url="{{ url()->full() }}" data-filter="zone_id" id="zone_id">
                                                <option value="" selected disabled>{{ translate('Select_Zone') }} <span>*</span>
                                                </option>
                                                @foreach (Zone::active()->orderBy('name')->get(['id','name']) as $z)
                                                    <option value="{{ $z['id'] }}"
                                                        {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                                        {{ $z['name'] }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="restaurant_id"></label>
                                            <select name="restaurant_id"
                                                    data-url="{{ url()->full() }}" data-filter="restaurant_id"
                                                    data-placeholder="{{ translate('messages.select_restaurant') }}"
                                                    class="form-control js-select2-custom h--45x set-filter"
                                                    id="restaurant_id" disabled>

                                                <option value="">{{ translate('Select_a_restaurant') }}</option>
                                                @foreach (Restaurant::active()->orderBy('name')->where('zone_id',request('zone_id'))->get(['id','name']) as $restaurant)
                                                    <option
                                                        value="{{ $restaurant['id'] }}" {{ request('restaurant_id') && request('restaurant_id')==$restaurant->id? 'selected':''}}>
                                                        {{ $restaurant->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <select name="category" id="category"
                                                    class="form-control js-select2-custom mx-1 h--45x set-filter"
                                                    title="{{ translate('Select_Category') }}"
                                                    data-url="{{ url()->full() }}" data-filter="category_id"
                                                    disabled>
                                                <option value="" selected>{{ translate('Select_Categories') }}</option>
                                                <option value="">{{ translate('All_Categories') }}</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $category == $item->id ? 'selected' : '' }}>
                                                        {{ Str::limit($item->name, 20, '...') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <form id="search-form" class="mw-100">
                                                <div class="input-group input-group-merge input-group-flush w-100">
                                                    <div class="input-group-prepend pl-2">
                                                        <div class="input-group-text">
                                                            <i class="tio-search"></i>
                                                        </div>
                                                    </div>
                                                    <input id="datatableSearch" type="search"
                                                           value="{{ $keyword ?? '' }}" name="search"
                                                           class="form-control flex-grow-1 pl-5 border rounded h--45x"
                                                           placeholder="{{ translate('Ex:_Search_Food_Name') }}"
                                                           aria-label="{{ translate('messages.search_here') }}"
                                                           disabled>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center" id="items">
                                @if(!$products->isEmpty())
                                    <div class="row g-3 mb-auto">
                                        @foreach ($products as $product)
                                            <div class="order--item-box item-box col-auto">
                                                @include('admin-views.pos._single_product', [
                                                    'product' => $product,
                                                    'restaurant_data ' => $restaurant_data,
                                                ])
                                            </div>
                                        @endforeach
                                    </div>
                                @else

                                    <div class="my-auto">
                                        <div class="search--no-found">
                                            <img src="{{dynamicAsset('/public/assets/admin/img/search-icon.png')}}" alt="img">
                                            <p>
                                                {{translate('messages.food_search_text_pos')}}
                                            </p>
                                        </div>
                                    </div>

                                @endif
                            </div>
                            <div class="card-footer border-0 pt-0">
                                {!! $products->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                    <div class="order--pos-right">
                        <div class="card">
                            <div class="card-header bg-light border-0 m-1">
                                <h5 class="card-title">
                                <span class="card-header-icon">
                                    <i class="tio-money-vs"></i>
                                </span>
                                    <span>
                                    {{translate('messages.billing_section')}}
                                </span>
                                </h5>
                            </div>
                            <div class="w-100">
                                <div class="d-flex flex-wrap flex-row p-2 add--customer-btn">
                                    <label for="customer"></label>
                                    <select id="customer" name="customer_id"
                                            data-placeholder="{{ translate('messages.select_customer') }}"
                                            class="js-data-example-ajax form-control">
                                    </select>
                                    <button class="btn btn--primary rounded font-regular" id="add_new_customer"
                                            type="button" data-toggle="modal" data-target="#add-customer"
                                            title="Add Customer">
                                        {{ translate('Add_new_customer') }}
                                    </button>
                                </div>
                            </div>
                            <div class="pos--delivery-options">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">
                                    <span class="card-title-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                        <span>{{ translate('Delivery_Information') }} <small>({{ translate('Home_Delivery') }})</small></span>
                                    </h5>
                                    <span class="delivery--edit-icon text-primary" id="delivery_address"
                                          data-toggle="modal" data-target="#paymentModal"><i
                                            class="tio-edit"></i></span>
                                </div>
                                <div class="pos--delivery-options-info d-flex flex-wrap" id="del-add">
                                    @include('admin-views.pos._address')
                                </div>
                            </div>
                            <div class='w-100' id="cart">
                                @include('admin-views.pos._cart', ['restaurant_data ' => $restaurant_data])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">
                </div>
            </div>
        </div>

        @php($order = Order::with(['details', 'restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'details.food' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }])->find(session('last_order')))
        @if ($order)
            @php(session(['last_order' => false]))
            <div class="modal fade" id="print-invoice" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ translate('messages.print_invoice') }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row ff-emoji">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <input type="button" class="btn text-white btn--primary non-printable print-Div"
                                           value="{{ translate('Proceed,_If_thermal_printer_is_ready.') }}"/>
                                    <a href="{{ url()->previous() }}"
                                       class="btn btn-danger non-printable">{{ translate('messages.back') }}</a>
                                </div>
                                <hr class="non-printable">
                            </div>
                            <div id="printableArea" class="col-12">
                                @include('admin-views.pos.invoice')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="modal fade" id="add-customer" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light py-3">
                        <h4 class="modal-title">{{ translate('add_new_customer') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.pos.customer-store') }}" method="post" id="product_form">
                            @csrf
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="f_name" class="input-label">{{ translate('first_name') }} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input id="f_name" type="text" name="f_name" class="form-control"
                                               value="{{ old('f_name') }}"
                                               placeholder="{{ translate('first_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="l_name" class="input-label">{{ translate('last_name') }} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input id="l_name" type="text" name="l_name" class="form-control"
                                               value="{{ old('l_name') }}" placeholder="{{ translate('last_name') }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="email" class="input-label">{{ translate('email') }}<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input id="email" type="email" name="email" class="form-control"
                                               value="{{ old('email') }}"
                                               placeholder="{{ translate('Ex:_ex@example.com') }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="phone" class="input-label">{{ translate('phone') }}
                                            ({{ translate('with_country_code') }})<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input id="phone" type="text" name="phone" class="form-control"
                                               value="{{ old('phone') }}" placeholder="{{ translate('phone') }}"
                                               required>
                                    </div>
                                </div>
                            </div>


                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                <button type="submit" id="submit_new_customer"
                                        class="btn btn--primary">{{ translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.49">
    </script>
    <script src="{{dynamicAsset('public/assets/admin/js/view-pages/pos.js')}}"></script>
    <script>

        "use strict";
        $(document).on('click', '.place-order-submit', function (event) {
            event.preventDefault();
            let customer_id = document.getElementById('customer');
            if (customer_id.value) {
                document.getElementById('customer_id').value = customer_id.value;
                let form = document.getElementById('order_place');
                form.submit();
            } else {
                toastr.error('{{ translate('messages.customer_not_selected') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });


        let extra_charge = 0;

        function initMap() {
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: {
                    lat: {{ $restaurant_data ? $restaurant_data['latitude'] : '23.757989' }},
                    lng: {{ $restaurant_data ? $restaurant_data['longitude'] : '90.360587' }}
                }
            });

            let zonePolygon = null;

            let infoWindow = new google.maps.InfoWindow();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        let myLatlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(myLatlng);
                        infoWindow.setContent("{{ translate('Location_found') }}");
                        infoWindow.open(map);
                        map.setCenter(myLatlng);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                handleLocationError(false, infoWindow, map.getCenter());
            }

            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
            const bounds = new google.maps.LatLngBounds();
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length === 0) {
                    return;
                }
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    if (!google.maps.geometry.poly.containsLocation(
                        place.geometry.location,
                        zonePolygon
                    )) {
                        toastr.error('{{ translate('messages.out_of_coverage') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        return false;
                    }

                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            @if ($restaurant_data)
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/{{ $restaurant_data->zone_id }}',
                dataType: 'json',
                success: function (data) {
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function (path) {
                        path.forEach(function (latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                                2),
                        });
                        let coordinates;
                        coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                        let geocoder;
                        geocoder = geocoder = new google.maps.Geocoder();
                        let latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[1]) {
                                    let address = results[1].formatted_address;
                                    // initialize services
                                    const geocoder = new google.maps.Geocoder();
                                    const service = new google.maps.DistanceMatrixService();
                                    // build request
                                    const origin1 = {
                                        lat: {{$restaurant_data['latitude']}},
                                        lng: {{$restaurant_data['longitude']}}
                                    };
                                    const origin2 = "{{$restaurant_data->address}}";
                                    const destinationA = address;
                                    const destinationB = {lat: coordinates['lat'], lng: coordinates['lng']};
                                    const request = {
                                        origins: [origin1, origin2],
                                        destinations: [destinationA, destinationB],
                                        travelMode: google.maps.TravelMode.DRIVING,
                                        unitSystem: google.maps.UnitSystem.METRIC,
                                        avoidHighways: false,
                                        avoidTolls: false,
                                    };

                                    service.getDistanceMatrix(request).then((response) => {
                                        let distancMeter = response.rows[0].elements[0].distance['value'];
                                        let distanceMile = distancMeter / 1000;
                                        let distancMileResult = Math.round((distanceMile + Number.EPSILON) * 100) / 100;
                                        document.getElementById('distance').value = distancMileResult;
                                            <?php
                                            $rest_sub = $restaurant_data->restaurant_sub;
                                            if (($restaurant_data->restaurant_model == 'commission' && $restaurant_data->self_delivery_system == 1)
                                                || ($restaurant_data->restaurant_model == 'subscription' && isset($rest_sub) && $rest_sub->self_delivery == 1)) {
                                                $per_km_shipping_charge = (float)$restaurant_data->per_km_shipping_charge;
                                                $minimum_shipping_charge = (float)$restaurant_data->minimum_shipping_charge;
                                                $maximum_shipping_charge = (float)$restaurant_data->maximum_shipping_charge;
                                                $increased = 0;
                                                $self_delivery_status = 1;
                                            } else {
                                                $per_km_shipping_charge = $restaurant_data->zone->per_km_shipping_charge ?? 0;
                                                $minimum_shipping_charge = $restaurant_data->zone->minimum_shipping_charge ?? 0;
                                                $maximum_shipping_charge = $restaurant_data->zone->maximum_shipping_charge ?? 0;
                                                $increased = 0;
                                                if ($restaurant_data->zone->increased_delivery_fee_status == 1) {
                                                    $increased = $restaurant_data->zone->increased_delivery_fee ?? 0;
                                                }
                                                $self_delivery_status = 0;
                                            }
                                            ?>

                                        $.get({
                                            url: '{{ route('admin.pos.extra_charge') }}',
                                            dataType: 'json',
                                            data: {
                                                distancMileResult: distancMileResult,
                                                self_delivery_status: {{ $self_delivery_status }},
                                            },
                                            success: function (data) {
                                                extra_charge = data;
                                                let original_delivery_charge = (distancMileResult * {{$per_km_shipping_charge}} > {{$minimum_shipping_charge}}) ? distancMileResult * {{$per_km_shipping_charge}} : {{$minimum_shipping_charge}};
                                                let delivery_amount = ({{ $maximum_shipping_charge }} > {{ $minimum_shipping_charge }} && original_delivery_charge + extra_charge > {{ $maximum_shipping_charge }} ? {{ $maximum_shipping_charge }} : original_delivery_charge + extra_charge);
                                                let with_increased_fee = (delivery_amount * {{ $increased }}) / 100;
                                                let delivery_charge = Math.round((delivery_amount + with_increased_fee + Number.EPSILON) * 100) / 100;
                                                document.getElementById('delivery_fee').value = delivery_charge;
                                                $('#delivery_fee').siblings('strong').html(delivery_charge + ' ({{ Helpers::currency_symbol() }})');

                                            },
                                            error: function () {
                                                let original_delivery_charge = (distancMileResult * {{$per_km_shipping_charge}} > {{$minimum_shipping_charge}}) ? distancMileResult * {{$per_km_shipping_charge}} : {{$minimum_shipping_charge}};
                                                let delivery_charge = Math.round((
                                                    ({{ $maximum_shipping_charge }} > {{ $minimum_shipping_charge }} && original_delivery_charge > {{ $maximum_shipping_charge }} ? {{ $maximum_shipping_charge }} : original_delivery_charge)
                                                    + Number.EPSILON) * 100) / 100;
                                                document.getElementById('delivery_fee').value = delivery_charge;
                                                $('#delivery_fee').siblings('strong').html(delivery_charge + ' ({{ Helpers::currency_symbol() }})');
                                            }
                                        });

                                    });

                                }
                            }
                        });
                    });
                },
            });
            @endif
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation ?
                    "Error: {{ translate('The_Geolocation_service_failed') }}." :
                    "Error: {{ translate('Your_browser_does_not_support_geolocation') }}."
            );
            infoWindow.open(map);
        }

        $(document).on('ready', function () {
            @if ($order)
            $('#print-invoice').modal('show');
            @endif
        });


        checkZone();
        checkRestZone();

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            let keyword = $('#datatableSearch').val();
            let nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });


        $(document).on('click', '.quick-View', function () {
            $.get({
                url: '{{ route('admin.pos.quick-view') }}',
                dataType: 'json',
                data: {
                    product_id: $(this).data('id')
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        $(document).on('click', '.quick-View-Cart-Item', function () {
            $.get({
                url: '{{ route('admin.pos.quick-view-cart-item') }}',
                dataType: 'json',
                data: {
                    product_id: $(this).data('product-id'),
                    item_key: $(this).data('item-key'),
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log("success...")
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        function checkAddToCartValidity() {
            let numberNotChecked = $('#add-to-cart-form input:checkbox:checked').length;
            let numberNotChecked2 = $('#add-to-cart-form input:radio:checked').length;
            return numberNotChecked > 0 || numberNotChecked2 > 0;

        }

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function (data) {
                        if (data.error === 'quantity_error') {
                            toastr.error(data.message);
                        } else {
                            $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                            $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        }
                    }
                });
            }
        }

        $(document).on('click', '.add-To-Cart', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            let form_id = 'add-to-cart-form';
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.data === 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "{{ translate('messages.product_already_added_in_cart') }}"
                        });
                        return false;
                    } else if (data.data === 2) {
                        updateCart();
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "{{ translate('messages.product_has_been_updated_in_cart') }}"
                        });
                        return false;
                    } else if (data.data === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: 'Sorry, product out of stock.'
                        });
                        return false;
                    } else if (data.data === 'variation_error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: data.message
                        });
                        return false;
                    }
                    $('.call-when-done').click();
                    toastr.success('{{ translate('messages.product_has_been_added_in_cart') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        });
        $(document).on('click', '.delivery-Address-Store', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            let form_id = 'delivery_address_store';

            $.post({
                url: '{{ route('admin.pos.add-delivery-address') }}',
                data: $('#' + form_id).serializeArray(),
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
                        $('#del-add').empty().html(data.view);
                    }
                    updateCart();
                    $('.call-when-done').click();
                },
                complete: function () {
                    $('#loading').hide();
                    $('#paymentModal').modal('hide');
                }
            });
        });

        function payableAmount(form_id = 'payable_store_amount') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.paid') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function () {
                    updateCart();
                    $('.call-when-done').click();
                },
                complete: function () {
                    $('#loading').hide();
                    $('#insertPayableAmount').modal('hide');
                }
            });
        }

        $(document).on('click', '.remove-From-Cart', function () {
            let key = $(this).data('product-id');
            $.post('{{ route('admin.pos.remove-from-cart') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function (data) {
                if (data.errors) {
                    for (let i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    updateCart();
                    toastr.info('{{ translate('messages.item_has_been_removed_from_cart') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }

            });
        });

        $(document).on('click', '.empty-Cart', function () {
            $.post('{{ route('admin.pos.emptyCart') }}', {
                _token: '{{ csrf_token() }}'
            }, function () {
                $('#del-add').empty();
                updateCart();
                toastr.info('{{ translate('messages.item_has_been_removed_from_cart') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            });
        });

        function updateCart() {
            $.post('<?php echo e(route('admin.pos.cart_items')); ?>?restaurant_id={{ request('restaurant_id') }}', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function (data) {
                $('#cart').empty().html(data);
            });
        }


        $(document).on('change', '.update-Quantity', function (event) {
            let element = $(event.target);
            let minValue = parseInt(element.attr('min'));
            let maxValue = parseInt(element.attr('max'));
            let valueCurrent = parseInt(element.val());

            let key = element.data('key');
            let oldvalue = element.data('value');
            if (valueCurrent >= minValue && maxValue >= valueCurrent) {
                $.post('{{ route('admin.pos.updateQuantity') }}', {
                    _token: '{{ csrf_token() }}',
                    key: key,
                    quantity: valueCurrent
                }, function () {
                    updateCart();
                });
            } else {
                element.val(oldvalue);
                Swal.fire({
                    icon: 'error',
                    title: "{{ translate('Cart') }}",
                    text: "{{ translate('quantity_unavailable') }}"
                });
            }

            // Allow: backspace, delete, tab, escape, enter and .
            if (event.type === 'keydown') {
                if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (event.keyCode === 65 && event.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (event.keyCode >= 35 && event.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        });


        $('#customer').select2({
            ajax: {
                url: '{{ route('admin.pos.customers') }}',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                __port: function (params, success, failure) {
                    let $request = $.ajax(params);
                    $request.then(success);
                    $request.fail(failure);
                    return $request;
                }
            }
        });

    </script>

@endpush

