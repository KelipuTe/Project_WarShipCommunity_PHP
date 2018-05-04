@extends('master.master')
@section('head')
    <link type="text/css" rel="stylesheet" href="/css/userCenter.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/user/center">个人中心</a></li>
@stop
@section('content')
    <div class="row master-user-center">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">个人中心</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked text-center">
                        {{--<li role="presentation" class="list-li">--}}
                            {{--<a href="#"><span class="fa fa-home fa-lg"></span> 信息总览</a>--}}
                        {{--</li>--}}
                        <li role="presentation" class="list-li">
                            <a href="/user/center/infoEdit"><span class="fa fa-edit fa-lg"></span> 我的信息</a>
                        </li>
                        <li role="presentation" class="list-li">
                            <a href="/user/center/avatarEdit"><span class="fa fa-user-circle-o fa-lg"></span> 我的头像</a>
                        </li>
                        {{--<li role="presentation" class="list-li">--}}
                            {{--<a href="#"><span class="fa fa-key fa-lg"></span> 账号安全</a>--}}
                        {{--</li>--}}
                        <li role="presentation" class="list-li">
                            <a href="/user/center/account"><span class="fa fa-dollar fa-lg"></span> 我的账户</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                @yield('center')
            </div>
        </div>
    </div>
@stop