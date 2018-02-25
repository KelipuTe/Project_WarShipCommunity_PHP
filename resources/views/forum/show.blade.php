@extends('forum.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="#" id="discussion-title"></a></li>
@stop
@section('content')
    {{--主体内容--}}
    <div class="row">
        {{--左侧部分--}}
        <div class="col-md-9">
            <div id="discussion">
                <discussion></discussion>
            </div>
            <template id="template-discussion">
                <div>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h2> @{{ discussion.title }}
                                <button class="btn btn-danger btn-sm pull-right" role="button" v-if="isUser" @click="softDelete()">爆破</button>
                            </h2>
                        </div>
                        <div class="panel-body" v-html="discussion.body"></div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('discussion',{
                    template:"#template-discussion",
                    data:function () {
                        return {
                            discussion: '',
                            isUser: false,
                            avatar: '',
                            username: ''
                        };
                    },
                    created:function () {
                        this.getDiscussion();
                    },
                    methods:{
                        getDiscussion:function(){
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                //判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/forum/getDiscussion/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.discussion = data.discussion;
                                    vm.isUser = data.isUser;
                                    vm.avatar = data.discussion.user_avatar[0].avatar;
                                    vm.username = data.discussion.username[0].username;
                                    $('#discussion-title').text(data.discussion.title);
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        softDelete:function(){
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                //判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/forum/softdelete/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == true){
                                        alert(data.message);
                                        window.location.href = "/forum";
                                    } else {
                                        alert(data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#discussion"
                });
            </script>
            {{--评论部分--}}
            <div id="comment-list">
                <comment-list></comment-list>
            </div>
            <div class="text-center">
                <ul id="page-list" class="pagination"></ul>
            </div>
            <template id="template-comment-list">
                <div>
                    <div v-for="comment in comments">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="media-object img-circle img-avatar-small" src="" :src="comment.user_avatar[0].avatar">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading forum-comment-line"> @{{comment.username[0].username}} </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" v-html="comment.body"></div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('comment-list',{
                    template:"#template-comment-list",
                    data:function () {
                        return {
                            comments: ''
                        };
                    },
                    created:function () {
                        this.getComments();
                    },
                    methods:{
                        getComments:function(){
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                //判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            var url = '/forum/getComments/' + discussion_id;
                            if( location.href.indexOf('?') != -1 ){
                                //判断是不是翻页后的地址，携带 ?page=number
                                var href = location.href.split('=');
                                url = '/forum/getComments/'+id+'?page='+ href[href.length-1];
                            }
                            $.ajax({
                                type:'GET',
                                url:url,
                                dataType:'json',
                                success:function (data) {
                                    vm.comments = data.comments.data;
                                    pageList(data.comments,'http://localhost/forum/show/'+id); // 构造分页按钮列表
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#comment-list"
                });
            </script>
            {{--创建评论部分--}}
            <div>
                @if(Auth::check())
                    @include('vendor.ueditor.assets'){{--使用ueditor编辑器时需要添加--}}
                    <div class="form-group">
                        {{--编辑器容器--}}
                        <script id="ue-container" type="text/plain"></script>
                    </div>
                    <div class="form-group">
                        <button id="submit" class="btn btn-success form-control">提交</button>
                    </div>
                    {{--实例化编辑器--}}
                    <script type="text/javascript">
                        var ue = UE.getEditor('ue-container');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');/* 设置CSRFtoken */
                        });
                    </script>
                    {{--可关闭的警告框--}}
                    <div class="master-alert">
                        <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
                    </div>
                @else
                    <a href="/user/login" class="form-control btn btn-success">登录参与讨论</a>
                @endif
            </div>
        </div>
        {{--右侧部分--}}
        <div class="col-md-3">
            {{--关于讨论--}}
            <div id="about-discussion">
                <about-discussion></about-discussion>
            </div>
            <template id="template-about-discussion">
                <div>
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">
                            <h2>@{{ countFollowedUser }}</h2>
                            <span>已关注</span>
                        </div>
                        <div class="panel-body">
                            {{--用户关注讨论按钮--}}
                            @if(Auth::check())
                                <button class="btn" :class="vbtnclass" @click="userDiscussionFollow()">
                                    <span class="glyphicon" :class="vbtnglyphicon"></span>
                                    <span v-text="vbtntext"></span>
                                </button>
                            @else
                                <a href="/user/login" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-star-empty"></span>关注该讨论
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('about-discussion',{
                    template:"#template-about-discussion",
                    data:function () {
                        return{
                            countFollowedUser:0,
                            userDiscussion:0
                        }
                    },
                    computed:{
                        vbtnclass:function () {
                            return this.userDiscussion ? 'btn-success' : 'btn-danger';
                        },
                        vbtnglyphicon:function () {
                            return this.userDiscussion ? 'glyphicon-star' : 'glyphicon-star-empty';
                        },
                        vbtntext:function () {
                            return this.userDiscussion ? '已关注' : '关注该讨论';
                        }
                    },
                    created:function () {
                        this.hasUserDiscussionFollow();
                    },
                    methods:{
                        hasUserDiscussionFollow:function () {
                            var vm = this; // 这里需要指定是 Vue.js 的 this 不是 JavaScript 的 this
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/follow/hasUserDiscussionFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.countFollowedUser = data.countFollowedUser;
                                    vm.userDiscussion = data.userDiscussion;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        userDiscussionFollow:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/follow/userDiscussionFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.countFollowedUser = data.countFollowedUser;
                                    vm.userDiscussion = data.userDiscussion;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#about-discussion"
                });
            </script>
            {{--关于作者--}}
            <div id="about-user">
                <about-user></about-user>
            </div>
            <template id="template-about-user">
                <div>
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">
                            <h3>关于作者</h3>
                        </div>
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <img src="" :src="userAvatar" class="img-avatar-small" :alt="username">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="#" :href="['/user/center/info/'+user_id]">@{{ username }}</a>
                                    </h4>
                                </div>
                            </div>
                            {{--用户等级--}}
                            <div id="liveness">
                                <span v-text="vliveness"></span>
                                <span v-text="vlevel"></span>
                            </div>
                            <div class="forum-user-statics">
                                <div class="forum-user-statics-item text-center">
                                    <div>讨 论</div>
                                    <div>@{{ countUserDiscussions }}</div>
                                </div>
                                <div class="forum-user-statics-item text-center">
                                    <div>回 复</div>
                                    <div>@{{ countUserComments }}</div>
                                </div>
                                <div class="forum-user-statics-item text-center">
                                    <div>关 注</div>
                                    <div>@{{ countUserFollowers }}</div>
                                </div>
                            </div>
                            <div id="user-user">
                                @if(Auth::check())
                                    {{--用户关注用户按钮--}}
                                    <button class="btn" :class="vbtnclass" @click="userUserFollow()">
                                        <span class="glyphicon " :class="vbtnglyphicon"></span>
                                        <span v-text="vbtntext"></span>
                                    </button>
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
            </template>
            <script>
                Vue.component('about-user',{
                    template:"#template-about-user",
                    data:function () {
                        return{
                            liveness:0, level:0,
                            userAvatar:'', username:'', user_id:'',
                            countUserDiscussions:0, countUserComments:0, countUserFollowers:0,
                            userUser:0
                        }
                    },
                    computed:{
                        vliveness:function () {
                            return '活跃值：'+this.liveness;
                        },
                        vlevel:function () {
                            return '等级：'+this.level;
                        },
                        vbtnclass:function () {
                            return this.userUser ? 'btn-success' : 'btn-danger';
                        },
                        vbtnglyphicon:function () {
                            return this.userUser ? 'glyphicon-star' : 'glyphicon-star-empty';
                        },
                        vbtntext:function () {
                            return this.userUser ? '已关注' : '关注TA';
                        }
                    },
                    created:function () {
                        this.getLivenessAndLevel();
                        this.hasUserUserFollow();
                    },
                    methods:{
                        getLivenessAndLevel:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/account/getLivenessAndLevel/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.liveness = data.liveness;
                                    vm.level = data.level;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        hasUserUserFollow:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/follow/hasUserUserFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.userAvatar = data.userAvatar;
                                    vm.username = data.username;
                                    vm.user_id = data.user_id;
                                    vm.countUserDiscussions = data.countUserDiscussions;
                                    vm.countUserComments = data.countUserComments;
                                    vm.countUserFollowers = data.countUserFollowers;
                                    vm.userUser = data.userUser;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        userUserFollow:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/follow/userUserFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.userUser = data.userUser;
                                    vm.countUserFollowers = data.countUserFollowers;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#about-user"
                });
            </script>
            <script>
                $(document).ready(function () {
                    /* 提交按钮 */
                    $('#submit').on('click', function () {
                        $('#submit').text('');
                        $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                        var href = location.href.split('/');
                        if( location.href.indexOf('?') != -1 ){
                            //判断是不是翻页后的地址，携带 ?page=number
                            href = location.href.split('?');
                            href = href[0].split('/');
                        }
                        var id = href[href.length-1];
                        $.ajax({
                            type: 'post',
                            url: '/forum/show/comment',
                            data: {
                                'discussion_id': id,
                                'body': UE.getEditor('ue-container').getContent()
                            },
                            dataType: 'json',
                            success: function (data) {
                                $('#submit').empty();
                                $('#submit').text('提交');
                                if(data.status == 1){
                                    window.location.reload();
                                } else if(data.status == 0){
                                    makeAlertBox('danger',data.message);
                                } else {
                                    makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                }
                            },
                            error: function (jqXHR) {
                                $('#submit').empty();
                                $('#submit').text('提交');
                                if(jqXHR.status == 422){
                                    // 遍历被 Laravel Request 拦截后返回的错误提示
                                    $.each(jqXHR.responseJSON.errors,function (index,value) {
                                        makeAlertBox('danger',value);
                                    });
                                }
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
@stop