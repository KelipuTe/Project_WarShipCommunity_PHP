@extends('forum.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <div class="jumbotron forum-jumbotron">
                <div class="container">
                    <h2>Welcome！
                        @if(Auth::check())
                            <a class="btn btn-danger btn-lg pull-right" href="/forum/create" role="button">发起新的讨论</a>
                        @else
                            <a class="btn btn-danger btn-lg pull-right" href="/user/login" role="button">登陆参与讨论</a>
                        @endif
                    </h2>
                </div>
            </div>
            {{--显示讨论列表--}}
            <div>
                @foreach($discussions as $discussion)
                    <div class="media forum-line">
                        <div class="media-left">
                            <img class="media-object img-circle img-avatar-small" src="{{$discussion->user->avatar}}">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="/forum/show/{{$discussion->id}}">{{$discussion->title}}</a>
                            </h4>
                            {{$discussion->user->username}}
                            <div class="pull-right">
                                <span>共{{count($discussion->comments)}}条回复，</span>
                                <span>用户{{$discussion->last_user_id}}最后更新于{{$discussion->updated_at->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="text-center">{!! $discussions->render() !!}</div>{{--分页--}}
            </div>
        </div>
    </div>
@stop