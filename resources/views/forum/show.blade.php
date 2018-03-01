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
                                <button class="btn btn-danger pull-right" role="button" v-if="isUser" @click="softDelete()"> 爆破 </button>
                            </h2>
                        </div>
                        <div class="panel-body" v-html="discussion.body"></div>
                        <div class="panel-footer">
                            <div class="clearfix">
                            <span> 浏览量 </span>
                            <span v-text="hot_discussion"></span>
                            @if(Auth::check())
                                <button class="btn btn-sm pull-right" :class="vbtnclass" role="button" @click="niceDiscussion()">
                                    <span class="fa" :class="vfaclass"></span>
                                    <span v-text="nice_discussion"></span>
                                    <span> 这篇讨论对我有用 </span>
                                </button>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('discussion',{
                    template:"#template-discussion",
                    data:function () {
                        return {
                            discussion: '',
                            hot_discussion: '',
                            nice_discussion:'',
                            isNice: false,
                            isUser: false,
                            avatar: '',
                            username: ''
                        };
                    },
                    computed:{
                        vfaclass:function () {
                            return this.isNice ? 'fa-thumbs-up' : 'fa-thumbs-o-up';
                        },
                        vbtnclass:function () {
                            return this.isNice ? 'btn-success' : 'btn-info';
                        }
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
                                    vm.hot_discussion = data.hot_discussion;
                                    vm.nice_discussion = data.nice_discussion;
                                    vm.isNice = data.isNice;
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
                        niceDiscussion:function () {
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
                                url:'/forum/niceDiscussion/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 1){
                                        vm.getDiscussion();
                                    } else if(data.status == -1) {
                                        makeAlertBox('info',data.message);
                                    } else {
                                        makeAlertBox('danger',data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        softDelete:function(){
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
                            <div class="panel-footer">
                                @if(Auth::check())
                                    <div class="clearfix">
                                        <button :id="['nice-comment-btn-'+comment.id]" class="btn btn-sm pull-right" :class="vbtnclass(comment.is_nice)" role="button" @click="niceComment(comment.id)">
                                            <span :id="['nice-comment-fa-'+comment.id]" class="fa" :class="vfaclass(comment.is_nice)"></span>
                                            <span :id="['nice-comment-text-'+comment.id]" v-text="comment.cache_nice_comment"></span>
                                            <span> 点赞 </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
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
                                    pageList(data.comments,'http://localhost/forum/show/'+discussion_id); // 构造分页按钮列表
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        niceComment:function (comment_id) {
                            $.ajax({
                                type:'GET',
                                url:'/forum/niceComment/' + comment_id,
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 1){
                                        $('#nice-comment-btn-'+data.comment_id).removeClass('btn-info').addClass('btn-success');
                                        $('#nice-comment-fa-'+data.comment_id).removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
                                        $('#nice-comment-text-'+data.comment_id).text(data.cache_nice_comment);
                                    } else if(data.status == -1) {
                                        makeAlertBox('info',data.message);
                                    } else {
                                        makeAlertBox('danger',data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        vfaclass:function (is_nice) {
                            return is_nice ? 'fa-thumbs-up' : 'fa-thumbs-o-up';
                        },
                        vbtnclass:function (is_nice) {
                            return is_nice ? 'btn-success' : 'btn-info';
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
                        <button id="submit" class="btn btn-success form-control"> 提交 </button>
                    </div>
                    {{--实例化编辑器--}}
                    <script type="text/javascript">
                        var ue = UE.getEditor('ue-container');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');/* 设置CSRFtoken */
                        });
                    </script>
                @else
                    <a href="/user/login" class="form-control btn btn-success"> 登录参与讨论 </a>
                @endif
            </div>
        </div>
        {{--右侧部分--}}
        <div class="col-md-3">
            {{--关于标签--}}
            <div id="about-tags">
                <about-tags></about-tags>
            </div>
            <template id="tamplate-about-tags">
                <div>
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">
                            <h3> 所属标签 </h3>
                        </div>
                        <div class="panel-body">
                            <button v-for="tag in tags" class="btn btn-warning btn-xs" style="margin: 5px 5px">
                                <span class="fa fa-tag"></span> @{{ tag.body }}
                            </button>
                        </div>
                        @if(Auth::check() && Auth::user()->id == $discussion->user_id)
                            <div class="panel-footer text-center">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" @click="getAllTags" data-toggle="modal" data-target="#tagsModal">
                                    <span class="fa fa-tags"></span> 添加标签
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="tagsModal" tabindex="-1" role="dialog" aria-labelledby="tagsModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="tagsModalLabel">
                                                    <span class="fa fa-tags"></span> 所有标签
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <button v-for="tag in allTags" class="btn" :class="changeClass(tag)" style="margin: 5px 10px" @click="changeTag(tag)">
                                                    <span class="fa fa-tag"></span> @{{ tag.body }}
                                                </button>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <label for="tags-body"><input type="text" id="tags-body" /></label>
                                                    <button id="tags-submit" type="button" class="btn btn-primary" @click="createTags">
                                                        <span class="fa fa-tags"></span> 新增标签
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </template>
            <script>
                Vue.component('about-tags',{
                    template:"#tamplate-about-tags",
                    data:function () {
                        return{
                            tags:'',
                            allTags:'',
                            isUser:false
                        }
                    },
                    computed:{

                    },
                    created:function () {
                        this.getTags();
                    },
                    methods:{
                        getTags:function () {
                            var vm = this; // 这里需要指定是 Vue.js 的 this 不是 JavaScript 的 this
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var id = href[href.length-1];
                            var type = 'discussion';
                            $.ajax({
                                type:'GET',
                                url:'/tag/getTags/' + type + '/' + id,
                                dataType:'json',
                                success:function (data) {
                                    vm.tags = data.tags;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        getAllTags:function () {
                            var vm = this;
                            $.ajax({
                                type:'GET',
                                url:'/tag/getAllTags',
                                dataType:'json',
                                success:function (data) {
                                    vm.allTags = data.allTags;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        createTags:function () {
                            var vm = this;
                            $('#tags-submit').text('');
                            $('#tags-submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                            $.ajax({
                                type: 'post',
                                url: '/tag/createTag',
                                data: {
                                    'body': $('#tags-body').val()
                                },
                                dataType: 'json',
                                success: function (data) {
                                    $('#tags-submit').empty();
                                    $('#tags-submit').text('新增标签');
                                    if(data.status == 1){
                                        $('#tags-body').val('');
                                        vm.getAllTags();
                                    } else if(data.status == 0){
                                        makeAlertBox('danger',data.message);
                                    } else {
                                        makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                    }
                                },
                                error: function (jqXHR) {
                                    $('#tags-submit').empty();
                                    $('#tags-submit').text('新增标签');
                                    if(jqXHR.status == 422){
                                        // 遍历被 Laravel Request 拦截后返回的错误提示
                                        $.each(jqXHR.responseJSON.errors,function (index,value) {
                                            makeAlertBox('danger',value);
                                        });
                                    }
                                }
                            });
                        },
                        changeTag:function (tag) {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var id = href[href.length-1];
                            var type = 'discussion';
                            $.ajax({
                                type: 'post',
                                url: '/tag/changeTag',
                                data: {
                                    'tag_id': tag.id,
                                    'type':type,
                                    'target':id
                                },
                                dataType: 'json',
                                success: function (data) {
                                    if(data.status == 1 || data.status == 2){
                                        $('#tagsModal').modal('toggle');
                                        vm.getTags();
                                    } else {
                                        makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                    }
                                },
                                error: function (jqXHR) {
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        changeClass:function (tag) {
                            var vm = this;
                            var isInArray = false;
                            $.each(vm.tags,function (index,obj) {
                                if(tag.body == obj.body){
                                    isInArray = true;
                                }
                            });
                            return isInArray ? 'btn-success' : 'btn-warning';
                        }
                    }
                });
                new Vue({
                    el:"#about-tags"
                });
            </script>
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
                        <div class="panel-body text-center">
                            {{--用户关注讨论按钮--}}
                            @if(Auth::check())
                                <button class="btn" :class="vbtnclass" @click="userDiscussionFollow()">
                                    <span class="fa" :class="vbtnglyphicon"></span>
                                    <span v-text="vbtntext"></span>
                                </button>
                            @else
                                <a href="/user/login" class="btn btn-danger">
                                    <span class="fa fa-star-o"></span> 关注该讨论
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
                            return this.userDiscussion ? 'fa-star' : 'fa-star-o';
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
                <div class="panel panel-info">
                    <div class="panel-heading text-center">
                        <div class="media">
                            <div class="media-left">
                                <img src="" :src="userAvatar" class="media-object img-circle img-avatar-small" :alt="username">
                            </div>
                            <div class="media-body">
                                <h3 class="media-heading" style="margin: 10px 0">
                                    <a href="#" :href="['/user/center/info/'+user_id]"> @{{ username }} </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body text-center">
                        {{--用户等级--}}
                        <div id="liveness">
                            <span v-text="vliveness"></span>
                            <span v-text="vlevel"></span>
                        </div>
                        <div class="forum-user-statics">
                            <div class="forum-user-statics-item text-center">
                                <div> 讨 论 </div>
                                <div> @{{ countUserDiscussions }} </div>
                            </div>
                            <div class="forum-user-statics-item text-center">
                                <div> 回 复 </div>
                                <div> @{{ countUserComments }} </div>
                            </div>
                            <div class="forum-user-statics-item text-center">
                                <div> 关 注 </div>
                                <div> @{{ countUserFollowers }} </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <div id="user-user">
                            @if(Auth::check())
                                {{--用户关注用户按钮--}}
                                <button class="btn" :class="vbtnclass" @click="userUserFollow()">
                                    <span class="fa " :class="vbtnglyphicon"></span>
                                    <span v-text="vbtntext"></span>
                                </button>
                                <button class="btn btn-primary">
                                    <span class="fa fa-envelope"></span>
                                    <span> 发私信 </span>
                                </button>
                            @else
                                <div class="forum-user-statics-item">
                                    <a href="/user/login" class="btn btn-danger">
                                        <span class="fa fa-star-o"></span> 关注 TA
                                    </a>
                                </div>
                            @endif
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
                            return this.userUser ? 'fa-star' : 'fa-star-o';
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