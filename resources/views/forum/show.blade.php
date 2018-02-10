@extends('forum.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="" id="discussion-id" name="{{$discussion->id}}">{{$discussion->title}}</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-9">
            {{--主体内容--}}
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h2>{{$discussion->title}}
                        @if(Auth::check() && Auth::user()->id == $discussion->user_id)
                            <a class="btn btn-danger btn-sm pull-right" href="" role="button">爆破</a>
                        @endif
                    </h2>
                </div>
                <div class="panel-body">
                    {!! $discussion->body !!}
                </div>
            </div>
            {{--评论部分--}}
            @foreach($comments as $comment)
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="media">
                            <div class="media-left">
                                <img class="media-object img-circle img-avatar-small" src="{{$comment->user->avatar}}">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading forum-comment-line">{{$comment->user->username}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        {!! $comment->body !!}
                    </div>
                </div>
            @endforeach
            <div class="text-center">{!! $comments->render() !!}</div>{{--分页--}}
            {{--创建评论部分--}}
            @if(Auth::check())
                @include('vendor.ueditor.assets'){{--使用ueditor编辑器时需要添加--}}
                @include('master.errorList')
                {!! Form::open(['url'=>'/forum/show/commit']) !!}
                {!! Form::hidden('discussion_id',$discussion->id) !!}
                <div class="form-group">
                    {{--编辑器容器--}}
                    <script id="container" name="body" type="text/plain"></script>
                </div>
                <div class="form-group">
                    {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
                {{--实例化编辑器--}}
                <script type="text/javascript">
                    var ue = UE.getEditor('container');
                    ue.ready(function() {
                        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');/* 设置CSRFtoken */
                    });
                </script>
            @else
                <a href="/user/login" class="form-control btn btn-success">登录参与讨论</a>
            @endif
        </div>
        <div class="col-md-3">
            {{--关于文章--}}
            <div class="panel panel-info">
                <div class="panel-heading text-center">
                    <h2>{{$discussion->hasFollowedUser()}}</h2>
                    <span>已关注</span>
                </div>
                <div class="panel-body">
                    {{--用户关注讨论的 Vue.js 组件--}}
                    <div id="user-discussion">
                        @if(Auth::check())
                            {{--用户关注讨论的 Vue.js 组件--}}
                            <user-discussion-button></user-discussion-button>
                        @else
                            <a href="/user/login" class="btn btn-danger">
                                <span class="glyphicon glyphicon-star-empty"></span>关注该讨论
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            {{--关于作者--}}
            <div class="panel panel-info">
                <div class="panel-heading text-center">
                    <h3>关于作者</h3>
                </div>
                <div class="panel-body">
                    <div class="media">
                        <div class="media-left">
                            <img src="{{$discussion->user->avatar}}" class="img-avatar-small" alt="{{$discussion->user->username}}">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="/user/userInfo/{{$discussion->user->id}}">{{$discussion->user->username}}</a>
                            </h4>
                        </div>
                    </div>
                    {{--用户等级的Vue.js组件--}}
                    <div id="liveness">
                        {{--用户等级的Vue.js组件--}}
                        <liveness></liveness>
                    </div>
                    <div class="forum-user-statics">
                        <div class="forum-user-statics-item text-center">
                            <div>讨 论</div>
                            <div>{{count($discussion->user->discussions)}}</div>
                        </div>
                        <div class="forum-user-statics-item text-center">
                            <div>回 复</div>
                            <div>{{count($discussion->user->comments)}}</div>
                        </div>
                        <div class="forum-user-statics-item text-center">
                            <div>关 注</div>
                            <div>{{count($discussion->user->userUserFollower)}}</div>
                        </div>
                    </div>
                    <div id="user-user">
                        @if(Auth::check())
                            {{--用户关注用户的Vue.js组件--}}
                            <div class="forum-user-statics">
                                {{--用户关注用户的Vue.js组件--}}
                                <user-user-button></user-user-button>
                            </div>
                        @else
                            <div class="forum-user-statics-item">
                                <a href="/user/login" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-star-empty"></span>关注 TA
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--引入经过编译的 Vuejs 组件--}}
    <script src="/js/forumShow.js" type="text/javascript" rel="script"></script>
@stop