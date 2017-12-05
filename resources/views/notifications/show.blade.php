@extends('master.master')
@section('breadCrumb')
    @parent
    <li><a href="">消息通知</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">消息通知</div>
                @foreach($notifications as $notification)
                    @include('notifications.'.snake_case(class_basename($notification->type)))
                @endforeach
            </div>
        </div>
    </div>
@stop