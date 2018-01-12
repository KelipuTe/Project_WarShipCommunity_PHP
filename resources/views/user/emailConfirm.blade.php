@extends('user.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">邮箱验证码激活页面</a></li>
@stop
@section('content')
    {{--网页部分--}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
            @if($status)
                <h2>账号： {{$email}} 激活成功！</h2>
            @else
                <h2>激活失败：校验码已过期</h2>
            @endif
        </div>
    </div>
@stop