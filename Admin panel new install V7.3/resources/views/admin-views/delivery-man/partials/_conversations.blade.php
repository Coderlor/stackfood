<div class="card h-100">
    <!-- Header -->
    <div class="card-header">
        <div class="chat-user-info w-100 d-flex align-items-center">
            <div class="chat-user-info-img">
                <img class="avatar-img onerror-image"
                     src="{{\App\CentralLogics\Helpers::onerror_image_helper($user['image'], dynamicStorage('storage/app/public/profile/').'/'.$user['image'], dynamicAsset('public/assets/admin/img/160x160/img1.jpg'), 'profile/') }}"
                     data-onerror-image="{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg"
                     alt="Image Description">
            </div>
            <div class="chat-user-info-content">
                <h5 class="mb-0 text-capitalize">
                    {{$user['f_name'].' '.$user['l_name']}}</h5>
                <span>{{ $user['phone'] }}</span>
            </div>
        </div>
    </div>

    <div class="card-body d-flex flex-column">
        <div class="scroll-down-2 full-height-from-300px">
            @foreach($convs as $con)
                @if($con->sender_id == $user->id)
                    <div class="pt1 pb-1">
                        <div class="conv-reply-1">
                            <h6>{{$con->message}}</h6>
                            @if($con->file!=null)
                            @foreach (json_decode($con->file) as $img)
                            <br>
                                <img class="w-100" src="{{dynamicStorage('storage/app/public/conversation').'/'.$img}}">
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="pl-1">
                        <small>
                            {{  \App\CentralLogics\Helpers::time_date_format($con->created_at)  }}
                        </small>
                    </div>
                @else
                    <div class="pt-1 pb-1">
                        <div class="conv-reply-2">
                            <h6>{{$con->message}}</h6>
                            @if($con->file!=null)
                            @foreach (json_decode($con->file) as $img)
                            <br>
                                <img class="w-100" src="{{dynamicStorage('storage/app/public/conversation').'/'.$img}}">
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="text-right pr-1">
                        <small>
                            {{  \App\CentralLogics\Helpers::time_date_format($con->created_at)  }}
                        </small>
                    </div>
                @endif
            @endforeach
            <div id="scroll-here"></div>
        </div>

    </div>
</div>
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
<script>
    "use strict";
    $(document).ready(function () {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        },0);
    });
</script>
