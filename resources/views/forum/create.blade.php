@extends('forum.app')
@section('breadCrumb')
    @parent
    <li><a href="">发起讨论</a></li>
@stop
@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            @include('error.errorList')
            {!! Form::open(['url'=>'/forum/store']) !!}
            <div class="form-group">
                {!! Form::label('title','Title:') !!}
                {!! Form::text('title',null,['style'=>'width: 80%']) !!}
            </div>
            <div class="form-group">
                <!-- 编辑器容器 -->
                <script id="container" name="body" type="text/plain"></script>
            </div>
            <div class="form-group">
                {!! Form::label('published_at','Published_at:') !!}
                {!! Form::input('date','published_at',date('Y-m-d'),['style'=>'width: 40%']) !!}
            </div>
            <div>
                {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
            {{--<form action="/forum/store" method="post">
                --}}{{--Laravel框架为了防止跨域请求攻击（CSRF）而为用户生成的随机令牌，
                post请求如果没有验证token，就出现报错信息，
                在form表单中添加一个隐藏域，携带token参数即可--}}{{--
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label for="title">标题：</label>
                    <input type="text" id="title" name="title" style="width: 80%">
                </div>
                <div class="form-group">
                    <!-- 编辑器容器 -->
                    <script id="container" name="body" type="text/plain"></script>
                </div>
                <div>
                    <input type="submit" value="提交" class="form-control btn btn-primary">
                </div>
            </form>--}}
            <!-- 实例化编辑器 -->
            <script type="text/javascript">
                var ue = UE.getEditor('container');
                ue.ready(function() {
                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
                });
            </script>
        </div>
    </div>
@stop