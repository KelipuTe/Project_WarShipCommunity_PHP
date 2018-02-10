@extends('office.master')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">{{$introduction->title}}</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <div class="jumbotron">
                <div class="container">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle img-avatar-middle" src="{{$introduction->user->avatar}}">
                        </div>
                        {{--主体内容--}}
                        <div class="media-body">
                            <h2 class="media-heading">{{$introduction->title}}
                                <a href="/user/center/info/{{$introduction->user->id}}"><span>@</span>{{$introduction->user->username}}</a>
                            </h2>
                            <h3>{!! $introduction->body !!}</h3>
                        </div>
                    </div>
                </div>
            </div>
            {{--显示所有的新人报道回复--}}
            @foreach($introduction->messages as $message)
                <hr>
                <div class="media">
                    <div class="media-left">
                        <img class="media-object img-circle img_avatar_small" src="{{$message->user->avatar}}">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{$message->user->username}}</h4>
                        {{$message->body}}
                    </div>
                </div>
            @endforeach
            {{--创建新人报道回复--}}
            @if(Auth::check())
                <hr>
                @if(Auth::user()->username != $introduction->user->username)
                    {!! Form::open(['url'=>'/office/show/welcome']) !!}
                    {!! Form::hidden('introduction_id',$introduction->id) !!}
                    <div class="form-group">
                        {!! Form::textarea('body','欢迎新人，我是'.Auth::user()->username,['class'=>'form-control']) !!}
                    </div>
                    <div>
                        {!! Form::submit('打个招呼',['class'=>'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                @endif
            @else
                <hr>
                <a href="/user/login" class="form-control btn btn-success">登录参与迎新</a>
            @endif
        </div>
    </div>
@stop