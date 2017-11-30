@extends('forum.app')
@section('breadCrumb')
    @parent
    <li><a href="" id="discussion-id" name="{{$discussion->id}}">{{$discussion->title}}</a></li>
@stop
@section('content')
    {{--主体部分--}}
    <div class="container">
        <div class="row">
            <div class="col-md-9" role="main">
                {{--主体内容--}}
                <div class="panel panel-default panel-danger">
                    <div class="panel-heading">
                        <h2>{{$discussion->title}}
                            @if(Auth::check() && Auth::user()->id == $discussion->user_id)
                                <a class="btn btn-primary btn-sm pull-right" href="" role="button">爆破</a>
                            @endif
                        </h2>
                    </div>
                    <div class="panel-body">
                        {!! $discussion->body !!}
                    </div>
                </div>
                {{--评论部分--}}
                @foreach($comments as $comment)
                    <div class="panel panel-default panel-success">
                        <div class="panel-heading">
                            <div class="media">
                                <div class="media-left">
                                    <img class="media-object img-circle img_avatar_small" src="{{$comment->user->avatar}}">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading forum-show-line">{{$comment->user->username}}</h4>
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
                    <hr>
                    <p>参与讨论</p>
                    @include('vendor.ueditor.assets'){{--使用编辑器时需要添加--}}
                    @include('error.errorList')
                    {!! Form::open(['url'=>'/forum/show/commit']) !!}
                    {!! Form::hidden('discussion_id',$discussion->id) !!}
                    <div class="form-group">
                        {{--编辑器容器--}}
                        <script id="container" name="body" type="text/plain"></script>
                    </div>
                    <div>
                        {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                    {{--实例化编辑器--}}
                    <script type="text/javascript">
                        var ue = UE.getEditor('container');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');/*设置CSRFtoken*/
                        });
                    </script>
                @else
                    <hr>
                    <a href="/user/login" class="form-control btn btn-success">登录参与讨论</a>
                @endif
            </div>
            <div class="col-md-3">
                {{--关于文章--}}
                <div class="panel panel-default panel-info">
                    <div class="panel-heading text-center">
                        <h2>{{$discussion->hasFollowedUser()}}</h2>
                        <span>已关注</span>
                    </div>
                    <div class="panel-body">
                        <div id="user-discussion">
                            @if(Auth::check())
                                {{--<a href="/follow/userDiscussionFollow/{{$discussion->id}}"
                                   class="btn btn-default {{Auth::user()->hasFollowedDiscussion($discussion->id) ? 'btn-success' : ''}}">
                                    <span class="glyphicon {{Auth::user()->hasFollowedDiscussion($discussion->id) ? 'glyphicon-star' : 'glyphicon-star-empty'}}"></span>
                                    {{Auth::user()->hasFollowedDiscussion($discussion->id) ? '已关注' : '关注该讨论'}}
                                </a>--}}
                                {{--用户关注讨论的Vue.js组件--}}
                                <user-discussion-button></user-discussion-button>
                                {{--用户关注讨论的Vue.js组件--}}
                            @else
                                <a href="/user/login"
                                   class="btn btn-default">
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    关注该讨论
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{--关于作者--}}
                <div class="panel panel-default panel-info">
                    <div class="panel-heading text-center">
                        <h3>关于作者</h3>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <img src="{{$discussion->user->avatar}}" class="img_avatar_small" alt="{{$discussion->user->username}}">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="/user/userInfo/{{$discussion->user->id}}">{{$discussion->user->username}}</a>
                                </h4>
                            </div>
                        </div>
                        {{--用户等级的Vue.js组件--}}
                        <div id="liveness">
                            <liveness></liveness>
                        </div>
                        {{--用户等级的Vue.js组件--}}
                        <div class="user-statics">
                            <div class="statics-item text-center">
                                <div>讨 论</div>
                                <div>{{count($discussion->user->discussions)}}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div>回 复</div>
                                <div>{{count($discussion->user->comments)}}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div>关 注</div>
                                <div>{{count($discussion->user->userUserFollower)}}</div>
                            </div>
                        </div>
                        <div id="user-user">
                            @if(Auth::check())
                                {{--用户关注用户的Vue.js组件--}}
                                <div class="user-statics">
                                    <user-user-button></user-user-button>
                                </div>
                                {{--用户关注用户的Vue.js组件--}}
                            @else
                                <div class="statics-item">
                                <a href="/user/login"
                                   class="btn btn-default">
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    关注该用户
                                </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--用户关注讨论的Vue.js组件--}}
    {{--<template id="template-user-discussion-button">
        <div>
            <button class="btn btn-default" :class="vclass" @click="userDiscussionFollow()">
                <span class="glyphicon " :class="vglyphicon"></span>
                <span v-text="vtext"></span>
            </button>
        </div>
    </template>
    <script>
        Vue.component('user-discussion-button',{
            template:'#template-user-discussion-button',
            data:function () {
                return{
                    userDiscussion:0
                }
            },
            created:function () {
                this.userDiscussion = this.hasUserDiscussionFollow();
            },
            methods:{
                hasUserDiscussionFollow:function () {
                    var vm = this;//这里需要指定是Vue.js的this不是JavaScript的this
                    var discussion_id = $('#discussion-id').attr('name');
                    $.ajax({
                        type:'GET',
                        url:'/VueHttp/hasUserDiscussionFollow/' + discussion_id,
                        dataType:'json',
                        success:function (data) {
                            vm.userDiscussion = data.userDiscussion;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                userDiscussionFollow:function () {
                    var vm = this;
                    var discussion_id = $('#discussion-id').attr('name');
                    $.ajax({
                        type:'GET',
                        url:'/VueHttp/userDiscussionFollow/' + discussion_id,
                        dataType:'json',
                        success:function (data) {
                            vm.userDiscussion = data.userDiscussion;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                }
            },
            computed:{
                vclass:function () {
                    return this.userDiscussion ? 'btn-success' : '';
                },
                vglyphicon:function () {
                    return this.userDiscussion ? 'glyphicon-star' : 'glyphicon-star-empty';
                },
                vtext:function () {
                    return this.userDiscussion ? '已关注' : '关注该讨论';
                }
            }
        });
        new Vue({
            el:'#user-discussion'
        });
    </script>--}}
    {{--用户关注讨论的Vue.js组件--}}
    {{--用户关注用户的Vue.js组件--}}
    {{--<template id="template-user-user-button">
        <div>
            <button class="btn btn-default" :class="vclass" @click="userUserFollow()">
                <span class="glyphicon " :class="vglyphicon"></span>
                <span v-text="vtext"></span>
            </button>
        </div>
    </template>
    <script>
        Vue.component('user-user-button',{
            template:'#template-user-user-button',
            data:function () {
                return{
                    userUser:0
                }
            },
            created:function () {
                this.userUser = this.hasUserUserFollow();
            },
            methods:{
                hasUserUserFollow:function () {
                    var vm = this;
                    var discussion_id = $('#discussion-id').attr('name');
                    $.ajax({
                        type:'GET',
                        url:'/follow/hasUserUserFollow/' + discussion_id,
                        dataType:'json',
                        success:function (data) {
                            vm.userUser = data.userUser;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                userUserFollow:function () {
                    var vm = this;
                    var discussion_id = $('#discussion-id').attr('name');
                    $.ajax({
                        type:'GET',
                        url:'/follow/userUserFollow/' + discussion_id,
                        dataType:'json',
                        success:function (data) {
                            vm.userUser = data.userUser;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                }
            },
            computed:{
                vclass:function () {
                    return this.userUser ? 'btn-success' : '';
                },
                vglyphicon:function () {
                    return this.userUser ? 'glyphicon-star' : 'glyphicon-star-empty';
                },
                vtext:function () {
                    return this.userUser ? '已关注' : '关注TA';
                }
            }
        });
        new Vue({
            el:'#user-user'
        });
    </script>--}}
    {{--用户关注用户的Vue.js组件--}}
    {{--用户等级的Vue.js组件--}}
    {{--<template id="template-liveness">
        <div>
            <span v-text="vliveness"></span>
            <span v-text="vlevel"></span>
        </div>
    </template>
    <script>
        Vue.component('liveness',{
            template:'#template-liveness',
            data:function () {
                return{
                    liveness:0,
                    level:0
                }
            },
            created:function () {
                this.init();
            },
            methods:{
                init:function () {
                    var vm = this;
                    var discussion_id = $('#discussion-id').attr('name');
                    $.ajax({
                        type:'GET',
                        url:'/account/getLiveness/' + discussion_id,
                        dataType:'json',
                        success:function (data) {
                            vm.liveness = data.liveness;
                            vm.level = data.level;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                }
            },
            computed:{
                vliveness:function () {
                    return '活跃值：'+this.liveness;
                },
                vlevel:function () {
                    return '等级：'+this.level;
                }
            }
        });
        new Vue({
            el:'#liveness'
        });
    </script>--}}
    {{--用户等级的Vue.js组件--}}
    {{--引入经过编译的Vuejs组件--}}
    <script src="/js/forumShow.js" type="text/javascript" rel="script"></script>
    {{--引入经过编译的Vuejs组件--}}
@stop