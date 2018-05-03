{{--用户关注用户消息通知模板--}}
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">用户关注</div>
            <div class="panel-body">
                <a href="/user/center/info/{{$notification->data['follower_id']}}"><span>@</span>{{$notification->data['follower']}}</a>
                <span>关注了你</span>
            </div>
        </div>
    </div>
</div>
