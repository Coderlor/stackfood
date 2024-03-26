@foreach($conversations as $conv)
@php($user= $conv->sender_type == 'delivery_man' ? $conv->receiver :  $conv->sender)
@if (isset($user))
    @php($unchecked=($conv->last_message?->sender_id == $user->id) ? $conv->unread_message_count : 0)
    <input type="hidden" id="deliver_man" value="{{ $dm->id }}">
    <div
        class="chat-user-info d-flex border-bottom p-3 align-items-center customer-list view-conv {{$unchecked!=0?'conv-active':''}}"
        data-url="{{route('admin.delivery-man.message-view',['conversation_id'=>$conv->id,'user_id'=>$user->id])}}" data-active-id="customer-{{$user->id}}" data-conv-id="{{ $conv->id }}" data-sender-id="{{ $user->id }}"
        id="customer-{{$user->id}}">
        <div class="chat-user-info-img d-none d-md-block">
            <img class="avatar-img onerror-image"
                 src="{{\App\CentralLogics\Helpers::onerror_image_helper($user['image'], dynamicStorage('storage/app/public/profile/').'/'.$user['image'], dynamicAsset('public/assets/admin/img/160x160/img1.jpg'), 'profile/') }}"
                 data-onerror-image="{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg"
                 alt="Image Description">
        </div>
        <div class="chat-user-info-content">
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3">{{$user['f_name'].' '.$user['l_name']}}</span>
                 <span class="{{$unchecked ? 'badge badge-info' : ''}}">{{$unchecked ? $unchecked : ''}}</span>
            </h5>
            <span>{{ $user['phone'] }}</span>
        </div>
    </div>
@else
    <div
        class="chat-user-info d-flex border-bottom p-3 align-items-center customer-list">
        <div class="chat-user-info-img d-none d-md-block">
            <img class="avatar-img"
                    src='{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg'
                    alt="Image Description">
        </div>
        <div class="chat-user-info-content">
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3">{{ translate('Account_not_found') }}</span>
            </h5>
        </div>
    </div>
@endif
@endforeach
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
