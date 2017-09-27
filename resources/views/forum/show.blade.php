@extends('forum.app')
@section('breadCrumb')
    @parent
    <li><a href="">{{$discussion->title}}</a></li>
@stop
@section('content')
    {{--主体部分--}}
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" role="main">
                {{--主体顶部巨幕--}}
                <div class="jumbotron">
                    <div class="container">
                        <div class="media">
                            <div class="media-left">
                                <img class="media-object img-circle img_avatar_middle" src="{{$discussion->user->avatar}}">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{$discussion->title}}
                                    @if(Auth::check() && Auth::user()->id == $discussion->user_id)
                                        <a class="btn btn-primary btn-lg pull-right" href="" role="button">爆破</a>
                                    @endif
                                </h4>
                                {{$discussion->user->username}}
                            </div>
                        </div>
                    </div>
                </div>
                {{--主体内容--}}
                <div>
                    {!! $discussion->body !!}
                </div>
                {{--评论部分--}}
                @foreach($discussion->comments as $comment)
                    <hr>
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle img_avatar_small" src="{{$comment->user->avatar}}">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">{{$comment->user->username}}</h4>
                            {!! $comment->body !!}
                        </div>
                    </div>
                @endforeach
                {{--创建评论部分--}}
                @if(Auth::check())
                    <hr>
                    @include('vendor.ueditor.assets')
                    @include('error.errorList')
                    {!! Form::open(['url'=>'/forum/show/commit']) !!}
                    {!! Form::hidden('discussion_id',$discussion->id) !!}
                    <div class="form-group">
                        <!-- 编辑器容器 -->
                        <script id="container" name="body" type="text/plain"></script>
                    </div>
                    <div>
                        {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var ue = UE.getEditor('container');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
                        });
                    </script>
                @else
                    <hr>
                    <a href="/user/login" class="form-control btn btn-success">登录参与讨论</a>
                @endif
            </div>
        </div>
    </div>
@stop