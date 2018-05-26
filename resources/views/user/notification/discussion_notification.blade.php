{{--讨论更新消息通知模板--}}
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">讨论更新</div>
            <div class="panel-body">
                {{ $notification->data['type'] }}{{ $notification->data['discussion_id'] }}
            </div>
        </div>
    </div>
</div>