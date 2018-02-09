@extends('user.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">个人中心</a></li>
@stop
@section('content')
    <div class="row master-user-center">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">个人中心</div>
                <div class="panel-body" style="padding: 0">
                    <dl class="list-dl">
                        <dt class="list-dt">
                            <a href="/user/userInfoEdit">
                                <span>我的信息</span>
                            </a>
                        </dt>
                        <dt class="list-dt">
                            <a href="/user/userInfoEdit">
                                <span>我的头像</span>
                            </a>
                        </dt>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">个人中心</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
@stop