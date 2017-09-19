@extends('forum.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" role="main">
                <div class="jumbotron">
                    <div class="container">
                        <h2>Welcome！
                            @if(Auth::check())
                                <a class="btn btn-primary btn-lg pull-right" href="/forum/create" role="button">发起新的讨论</a>
                            @else
                                <a class="btn btn-success btn-lg pull-right" href="/user/login" role="button">登陆参与讨论</a>
                            @endif
                        </h2>
                    </div>
                </div>
                <div>
                    @foreach($discussions as $discussion)
                        <div class="media">
                            <div class="media-left">
                                <img class="media-object img-circle img_avatar" alt="50x50" src="{{$discussion->user->avatar}}">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="/forum/show/{{$discussion->id}}">{{$discussion->title}}</a>
                                    <div class="pull-right">
                                        <span>{{count($discussion->comments)}}回复</span>
                                    </div>
                                </h4>
                                {{$discussion->user->username}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop