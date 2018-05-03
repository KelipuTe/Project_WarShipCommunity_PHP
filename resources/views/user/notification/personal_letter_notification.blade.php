{{--私信消息通知模板--}}
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">用户私信</div>
            <div class="panel-body">
                <a href="/user/center/info/{{$notification->data['from_user_id']}}">
                    <span>@</span>{{$notification->data['from_user_username']}}
                </a>发来私信
            </div>
        </div>
    </div>
</div>