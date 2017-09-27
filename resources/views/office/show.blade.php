@extends('office.app')
@section('breadCrumb')
    @parent
    <li><a href="">{{$introduction->title}}</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" role="main">
                <div class="jumbotron">
                    <div class="container">
                        <div class="media">
                            <div class="media-left">
                                <img class="media-object img-circle img_avatar_middle" src="{{$introduction->user->avatar}}">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{$introduction->title}}
                                    @if(Auth::check() && Auth::user()->id == $introduction->user_id)
                                        <a class="btn btn-primary btn-lg pull-right" href="/office/show/{{$introduction->id}}/edit" role="button">修改</a>
                                    @endif
                                </h4>
                                {{$introduction->user->name}}
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    {!! $introduction->body !!}
                </div>
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
    </div>
@stop