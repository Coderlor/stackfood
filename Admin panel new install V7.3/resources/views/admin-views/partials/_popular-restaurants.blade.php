<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{ dynamicAsset('/public/assets/admin/img/dashboard/most-popular.png') }}" alt="dashboard"
            class="card-header-icon">
        {{ translate('Most_Popular_Restaurants') }}
    </h5>
    @php($params = session('dash_params'))
    @if ($params['zone_id'] != 'all')
        @php($zone_name = \App\Models\Zone::where('id', $params['zone_id'])->first()->name)
    @else
        @php($zone_name = translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{ translate('messages.zone') }} : {{ $zone_name }}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <ul class="most-popular">
        @foreach ($popular as $key => $item)
            <li data-url="{{ route('admin.restaurant.view', $item->restaurant_id) }}" class="cursor-pointer redirect-url">
                <div class="img-container">
                    <img class="onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/1.png')}}"
                         src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                        $item->restaurant['logo'] ?? '',
                        dynamicStorage('storage/app/public/restaurant').'/'.$item->restaurant['logo'] ?? '',
                        dynamicAsset('public/assets/admin/img/100x100/1.png'),
                        'restaurant/'
                    ) }}" alt="{{translate('store')}}">
                    <span class="ml-2">
                        {{ Str::limit($item->restaurant->name ?? translate('messages.Restaurant_deleted!'), 20, '...') }} </span>
                </div>
                <span class="count">
                    {{ $item['count'] }} <i class="tio-heart"></i>
                </span>
            </li>
        @endforeach
    </ul>
</div>
