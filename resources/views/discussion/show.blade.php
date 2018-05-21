@extends('discussion.app')
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
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h2> @{{ discussion.title }}
                            <div class="pull-right">
                                <button class="btn btn-danger" @click="blacklist(discussion.id)">
                                    <span class="fa fa-hand-stop-o fa-lg"></span>举报</button>
                                <button class="btn btn-danger" v-if="isOwner" @click="setTop(discussion.id)">
                                    <span class="fa fa-thumb-tack fa-lg"></span>置顶</button>
                                <button class="btn btn-danger" v-if="isOwner" @click="softDelete(discussion.id)">
                                    <span class="fa fa-trash-o fa-lg"></span>爆破</button>
                            </div>
                        </h2>
                    </div>
                    <div class="panel-body" v-html="discussion.body"></div>
                    <div class="panel-footer">
                        <div class="clearfix">
                            <span> 浏览量 </span>
                            <span v-text="hot_discussion"></span>
                            <button class="btn btn-sm pull-right" :class="vbtnclass" v-if="isLogin" @click="niceDiscussion(discussion.id)">
                                <span class="fa fa-lg" :class="vfaclass"></span>
                                <span v-text="nice_discussion"></span>
                                <span> 这篇讨论对我有用 </span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('discussion',{
                    template:"#template-discussion",
                    data:function () {
                        return {
                            discussion: '', hot_discussion: '', nice_discussion: '',
                            isNice: false, isOwner: false, isLogin: false
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
                        if($('meta[name="api-token"]').attr('content') != 'Bearer'){
                            this.isLogin = true;
                        }
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
                                url:'/discussion/getDiscussion/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.discussion = data.discussion;
                                    vm.hot_discussion = data.hot_discussion;
                                    vm.nice_discussion = data.nice_discussion;
                                    vm.isNice = data.isNice;
                                    vm.isOwner = data.isOwner;
                                    $('#discussion-title').text(data.discussion.title);
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        niceDiscussion:function (discussion_id) {
                            var vm = this;
                            $.ajax({
                                type:'GET',
                                url:'/discussion/niceDiscussion/' + discussion_id,
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
                        softDelete:function(discussion_id){
                            $.ajax({
                                type:'GET',
                                url:'/discussion/softdelete/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == true){
                                        alert(data.message);
                                        window.location.href = "/discussion";
                                    } else {
                                        alert(data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        blacklist:function(discussion_id){
                            $.ajax({
                                type:'post',
                                url:'/office/blacklist/report',
                                data:{
                                    'type': 'discussion',
                                    'target': discussion_id,
                                    'explain': 'null'
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 1){
                                        alert(data.message);
                                    } else {
                                        alert(data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        setTop:function(discussion_id){
                            $.ajax({
                                type:'get',
                                url:'/discussion/setTop',
                                data:{
                                    'target': discussion_id,
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 'failed'){
                                        alert(data.message);
                                    } else {
                                        alert('success');
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({ el:"#discussion" });
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
                                        <img class="media-object img-circle img-avatar-small" src="" :src="comment.relatedInfo.avatar">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading comment-line">
                                            @{{comment.relatedInfo.username}}
                                            <div class="pull-right" v-if="isLogin">
                                                <button class="btn btn-danger" role="button" @click="blacklist(comment.id)">
                                                    <span class="fa fa-hand-stop-o fa-lg"></span>举报</button>
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" v-html="comment.body"></div>
                            <div class="panel-footer">
                                <div class="clearfix">
                                    <button :id="['nice-comment-btn-'+comment.id]" role="button" v-if="isLogin" @click="niceComment(comment.id)"
                                            class="btn btn-sm pull-right" :class="vbtnclass(comment.relatedInfo.isNice)">
                                        <span :id="['nice-comment-fa-'+comment.id]" class="fa fa-lg" :class="vfaclass(comment.relatedInfo.isNice)"></span>
                                        <span :id="['nice-comment-text-'+comment.id]" v-text="comment.relatedInfo.cache_nice_comment"></span>
                                        <span>点赞</span>
                                    </button>
                                    <a href="/user/login" class="btn btn-info btn-sm pull-right" v-else="isLogin">
                                        <span class="fa fa-thumbs-o-up fa-lg"></span>
                                        <span v-text="comment.relatedInfo.cache_nice_comment"></span>
                                        <span>点赞</span>
                                    </a>
                                </div>
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
                            isLogin: false,
                            comments: ''
                        };
                    },
                    created:function () {
                        if($('meta[name="api-token"]').attr('content') != 'Bearer'){
                            this.isLogin = true;
                        }
                        this.getComments();
                    },
                    methods:{
                        getComments:function(){
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                // 判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            var url = '/discussion/getComments/' + discussion_id;
                            if( location.href.indexOf('?') != -1 ){
                                // 判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('=');
                                url = '/discussion/getComments/' + discussion_id + '?page='+ href[href.length-1];
                            }
                            $.ajax({
                                type:'get',
                                url:url,
                                dataType:'json',
                                success:function (data) {
                                    vm.comments = data.comments.data;
                                    pageList(data.comments,'http://localhost/discussion/show/'+discussion_id); // 构造分页按钮列表
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        niceComment:function (comment_id) {
                            $.ajax({
                                type:'get',
                                url:'/discussion/niceComment/' + comment_id,
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
                        blacklist:function(comment_id){
                            $.ajax({
                                type:'post',
                                url:'/office/blacklist/report',
                                data:{
                                    'type': 'comment',
                                    'target': comment_id,
                                    'explain': 'null'
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 1){
                                        alert(data.message);
                                    } else {
                                        alert(data.message);
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        vfaclass:function (isNice) {
                            return isNice ? 'fa-thumbs-up' : 'fa-thumbs-o-up';
                        },
                        vbtnclass:function (isNice) {
                            return isNice ? 'btn-success' : 'btn-info';
                        }
                    }
                });
                new Vue({el:"#comment-list"});
            </script>
            {{--创建评论部分--}}
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
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); /* 设置CSRFtoken */
                        });

                        $('#submit').on('click', function () {
                            $('#submit').text('');
                            $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                // 判断是不是翻页后的地址，携带 ?page=number
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var id = href[href.length-1];
                            $.ajax({
                                type: 'post',
                                url: '/discussion/show/comment',
                                data: {
                                    'discussion_id': id,
                                    'body': UE.getEditor('ue-container').getContent()
                                },
                                dataType: 'json',
                                complete:function(){
                                    $('#submit').empty();
                                    $('#submit').text('提交');
                                },
                                success: function (data) {
                                    if(data.status == 1){
                                        window.location.reload();
                                    } else if(data.status == 0){
                                        makeAlertBox('danger',data.message);
                                    } else {
                                        makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                    }
                                },
                                error: function (jqXHR) {
                                    if(jqXHR.status == 422){
                                        // 遍历被 Laravel Request 拦截后返回的错误提示
                                        $.each(jqXHR.responseJSON.errors,function (index,value) {
                                            makeAlertBox('danger',value);
                                        });
                                    }
                                }
                            });
                        });
                    </script>
                @else
                    <a href="/user/login" class="form-control btn btn-success"> 登录参与讨论 </a>
                @endif
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
                                <span class="fa fa-tag fa-lg"></span> @{{ tag.body }}
                            </button>
                        </div>
                        <div class="panel-footer text-center" v-if="isOwner">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" @click="getAllTags" data-toggle="modal" data-target="#tagsModal">
                                <span class="fa fa-tags fa-lg"></span> 添加标签
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="tagsModal" tabindex="-1" role="dialog" aria-labelledby="tagsModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="tagsModalLabel">
                                                <span class="fa fa-tags fa-lg"></span> 所有标签
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <button v-for="tag in allTags" class="btn" :class="changeClass(tag)" style="margin: 5px 10px" @click="changeTag(tag)">
                                                <span class="fa fa-tag fa-lg"></span> @{{ tag.body }}
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <label for="tags-body"><input type="text" id="tags-body" /></label>
                                                <button id="tags-submit" type="button" class="btn btn-primary" @click="createTags">
                                                    <span class="fa fa-tags fa-lg"></span> 新增标签
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('about-tags',{
                    template:"#tamplate-about-tags",
                    data:function () {
                        return{
                            tags: '', allTags: '',
                            isOwner:false
                        }
                    },
                    created:function () {
                        this.getTags();
                    },
                    methods:{
                        getTags:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var id = href[href.length-1];
                            $.ajax({
                                type:'get',
                                url:'/tag/getTags/discussion/' + id,
                                dataType:'json',
                                success:function (data) {
                                    vm.tags = data.tags;
                                    vm.isOwner = data.isOwner;
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
                                data: {'body': $('#tags-body').val()},
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
                                    'tag_id':tag.id,
                                    'type':type, 'target':id
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
                new Vue({el:"#about-tags"});
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
                            <button class="btn" :class="vfollowbtnclass" @click="userDiscussionFollow()" v-if="isLogin">
                                <span class="fa fa-lg" :class="vfollowbtnglyphicon"></span>
                                <span v-text="vfollowbtntext"></span>
                            </button>
                            <a href="/user/login" class="btn btn-danger" v-else="isLogin">
                                <span class="fa fa-star-o fa-lg"></span> 关注该讨论
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('about-discussion',{
                    template:"#template-about-discussion",
                    data:function () {
                        return{
                            countFollowedUser: 0,
                            isLogin: '' ,isFollowed: 0
                        }
                    },
                    computed:{
                        vfollowbtnclass:function () {
                            return this.isFollowed ? 'btn-success' : 'btn-danger';
                        },
                        vfollowbtnglyphicon:function () {
                            return this.isFollowed ? 'fa-star' : 'fa-star-o';
                        },
                        vfollowbtntext:function () {
                            return this.isFollowed ? '已关注' : '关注该讨论';
                        }
                    },
                    created:function () {
                        this.hasUserDiscussionFollow();
                    },
                    methods:{
                        hasUserDiscussionFollow:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'get',
                                url:'/follow/hasUserDiscussionFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.countFollowedUser = data.countFollowedUser;
                                    vm.isLogin = data.isLogin;
                                    vm.isFollowed = data.isFollowed;
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
                                    vm.isFollowed = data.isFollowed;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({el:"#about-discussion"});
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
                        <div class="user-statics">
                            <div class="user-statics-item text-center">
                                <div>讨 论</div>
                                <div>@{{ countUserDiscussions }}</div>
                            </div>
                            <div class="user-statics-item text-center">
                                <div>回 复</div>
                                <div>@{{ countUserComments }}</div>
                            </div>
                            <div class="user-statics-item text-center">
                                <div>关 注</div>
                                <div>@{{ countUserFollowers }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <div class="user-statics-item" v-if="isLogin">
                            {{--关注用户按钮--}}
                            <button class="btn" :class="vfollowbtnclass" @click="userUserFollow()">
                                <span class="fa fa-lg" :class="vfollowbtnglyphicon"></span>
                                <span v-text="vfollowbtntext"></span>
                            </button>
                            {{--发送私信按钮--}}
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#letterModal" @click="getMessage()">
                                <span class="fa fa-paper-plane-o fa-lg"></span>
                                <span> 发私信 </span>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="letterModal" tabindex="-1" role="dialog" aria-labelledby="letterModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="letterModalLabel"> 发送私信 </h4>
                                        </div>
                                        <div class="modal-body personal-letter-container">
                                            <div v-for="letter in personalLetters" class="row">
                                                <div class="panel personal-letter" :class="vletterclass(letter)">
                                                    <div class="panel-heading">@{{ letter.body }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <textarea name="message-body" class="form-control " id="message-body" rows="1" style="resize: none"></textarea>
                                                </div>
                                                <div class="col-md-2">
                                                    <button id="message-submit" type="button" class="btn btn-primary" @click="sendMessage">
                                                        <span class="fa fa-paper-plane-o fa-lg"></span> 发送
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="user-statics-item" v-else="isLogin">
                            <a href="/user/login" class="btn btn-danger">
                                <span class="fa fa-star-o fa-lg"></span> 关注 TA
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('about-user',{
                    template:"#template-about-user",
                    data:function () {
                        return{
                            user_id: '', username: '', userAvatar: '',
                            countUserDiscussions: 0, countUserComments: 0, countUserFollowers: 0,
                            isLogin: false, isFollowed: 0,
                            personalLetters: ''
                        }
                    },
                    computed:{
                        vfollowbtnclass:function () {
                            return this.isFollowed ? 'btn-success' : 'btn-danger';
                        },
                        vfollowbtnglyphicon:function () {
                            return this.isFollowed ? 'fa-star' : 'fa-star-o';
                        },
                        vfollowbtntext:function () {
                            return this.isFollowed ? '已关注' : '关注TA';
                        }
                    },
                    created:function () {
                        if($('meta[name="api-token"]').attr('content') != 'Bearer'){
                            this.isLogin = true;
                        }
                        this.hasUserUserFollow();
                    },
                    methods:{
                        hasUserUserFollow:function () {
                            var vm = this;
                            var href = location.href.split('/');
                            if( location.href.indexOf('?') != -1 ){
                                href = location.href.split('?');
                                href = href[0].split('/');
                            }
                            var discussion_id = href[href.length-1];
                            $.ajax({
                                type:'get',
                                url:'/follow/hasUserUserFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.user_id = data.user_id;
                                    vm.username = data.username;
                                    vm.userAvatar = data.userAvatar;
                                    vm.countUserDiscussions = data.countUserDiscussions;
                                    vm.countUserComments = data.countUserComments;
                                    vm.countUserFollowers = data.countUserFollowers;
                                    vm.isFollowed = data.isFollowed;
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
                                type:'get',
                                url:'/follow/userUserFollow/' + discussion_id,
                                dataType:'json',
                                success:function (data) {
                                    vm.userUser = data.userUser;
                                    vm.countUserFollowers = data.countUserFollowers;
                                }
                            });
                        },
                        getMessage:function(){
                            var vm = this;
                            $.ajax({
                                type:'get',
                                url:'/notification/getPersonalLetters',
                                data:{'other_user_id':vm.user_id},
                                dataType:'json',
                                success:function (data) {
                                    vm.personalLetters = data.personalLetters.data;
                                    vm.personalLetters =  vm.personalLetters.reverse();
                                }
                            });
                        },
                        sendMessage:function(){
                            var vm = this;
                            $.ajax({
                                type:'post',
                                url:'/notification/personalLetterStore',
                                data:{
                                    'to_user_id':vm.user_id,
                                    'body':$('#message-body').val()
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status == 1) {
                                        vm.personalLetters.push(data.personalLetter);
                                        $('#message-body').val('');
                                    } else {
                                        alert(data.message);
                                    }
                                }
                            });
                        },
                        vletterclass:function(letter){
                            return letter.from_user_id == this.user_id ? 'panel-success pull-left' : 'panel-primary pull-right';
                        }
                    }
                });
                new Vue({el:"#about-user"});
            </script>
        </div>
    </div>
@stop