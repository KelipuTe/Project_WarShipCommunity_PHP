@extends('forum.app')
@section('head')
    <script type="text/javascript" src="https://cdn.bootcss.com/vue/2.4.4/vue.js"></script>
@stop
@section('breadCrumb')
    @parent
    <li><a href="" id="discussion-id" name="{{$discussion->id}}">{{$discussion->title}}</a></li>
@stop
@section('content')
    {{--主体部分--}}
    <div class="container">
        <div class="row">
            <div class="col-md-8" role="main">
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
            <div class="col-md-4">
                {{--关于文章--}}
                <div class="panel panel-default">
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
                                <a href="/VueHttp/userDiscussionFollow/{{$discussion->id}}"
                                   class="btn btn-default">
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    关注该讨论
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{--关于作者--}}
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3>关于作者</h3>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img src="{{$discussion->user->avatar}}" class="img_avatar_small" alt="{{$discussion->user->username}}">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="#">{{$discussion->user->username}}</a>
                                </h4>
                            </div>
                            <div class="user-statics">
                                <div class="statics-item text-center">
                                    <div class="statics-text">讨论</div>
                                    <div class="statics-count">{{count($discussion->user->discussions)}}</div>
                                </div>
                                <div class="statics-item text-center">
                                    <div class="statics-text">回复</div>
                                    <div class="statics-count">{{count($discussion->user->comments)}}</div>
                                </div>
                                <div class="statics-item text-center">
                                    <div class="statics-text">关注者</div>
                                    <div class="statics-count">{{count($discussion->user->userUserFollower)}}</div>
                                </div>
                            </div>
                        </div>
                        <div id="user-user">
                            @if(Auth::check())
                                {{--用户关注用户的Vue.js组件--}}
                                <user-user-button></user-user-button>
                                {{--用户关注用户的Vue.js组件--}}
                            @else
                                <a href="/follow/userUserFollow/{{$discussion->user->id}}"
                                   class="btn btn-default">
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                    关注该用户
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--用户关注讨论的Vue.js组件--}}
    <template id="template-user-discussion-button">
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
    </script>
    {{--用户关注讨论的Vue.js组件--}}
    {{--用户关注用户的Vue.js组件--}}
    <template id="template-user-user-button">
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
                        url:'/VueHttp/hasUserUserFollow/' + discussion_id,
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
                        url:'/VueHttp/userUserFollow/' + discussion_id,
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
    </script>
    {{--用户关注用户的Vue.js组件--}}
@stop