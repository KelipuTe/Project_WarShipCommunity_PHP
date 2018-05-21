@extends('master.master')
@section('breadCrumb')
    @parent
    <li><a href="/notification/center">消息中心</a></li>
@stop
@section('content')
    <div class="row master-user-center">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">消息中心</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked text-center">
                        <li role="presentation" class="list-li">
                            <a href="/notification/center"><span class="fa fa-book fa-lg"></span> 所有消息</a>
                        </li>
                        <li role="presentation" class="list-li">
                            <a href="/notification/unread"><span class="fa fa-envelope-o fa-lg"></span> 未读消息</a>
                        </li>
                        {{--<li role="presentation" class="list-li">--}}
                            {{--<a href="#"><span class="fa fa-flag-o fa-lg"></span> 站内通知</a>--}}
                        {{--</li>--}}
                        <li role="presentation" class="list-li">
                            <a href="/notification/personalLetter"><span class="fa fa-paper-plane-o fa-lg"></span> 私信消息</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                @yield('notification')
            </div>
        </div>
    </div>
@stop