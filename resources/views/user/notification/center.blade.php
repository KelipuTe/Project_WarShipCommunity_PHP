@extends('user.notification.master')
@section('notification')
    <div class="panel-heading"> 所有消息 </div>
    <div class="panel-body">
        @foreach($notifications as $notification)
            {{--按照消息通知的类别区分不同的消息通知，并载入不同的模板--}}
            @include('user.notification.'.snake_case(class_basename($notification->type)))
        @endforeach
    </div>
@stop