@extends('discussion.app')
@section('content')
    <div class="row">
        <div class="col-md-9" role="main">
            {{--显示讨论列表--}}
            <div id="discussion-list">
                <discussion-list></discussion-list>
            </div>
            <div class="text-center">
                <ul id="page-list" class="pagination"></ul>
            </div>
            <template id="template-discussion-list">
                <div>
                    <div v-for="discussion in discussions">
                        <div class="media forum-line">
                            <div class="media-left">
                                <img class="media-object img-circle img-avatar-small" style="margin-left: 20px" src="" :src="discussion.relatedInfo.avatar">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="#" :href="['/discussion/show/'+discussion.id]"> @{{ discussion.title }} </a>
                                    <span class="label label-danger" v-if="discussion.set_top==true">置顶</span>
                                </h4>
                                <span>
                                    用户：@{{ discussion.relatedInfo.lastUsername }}，
                                    最后更新于：@{{ discussion.relatedInfo.update_diffForHumans }}
                                </span>
                                <div class="pull-right" style="margin-right: 20px">
                                    <span>共 @{{ discussion.relatedInfo.countComments }} 条回复</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('discussion-list',{
                    template:"#template-discussion-list",
                    data:function(){
                        return {discussions: ''};
                    },
                    created:function(){
                        this.getDiscussions();
                    },
                    methods:{
                        getDiscussions:function(){
                            var vm = this;
                            var url = '/api/discussion/getDiscussions';
                            if( location.href.indexOf('=') != -1 ){
                                // 判断是不是翻页后的地址，携带 ?page=number
                                var href = location.href.split('=');
                                url = '/api/discussion/getDiscussions?page='+ href[href.length-1];
                            }
                            $.ajax({
                                type:'get',
                                url:url,
                                dataType:'json',
                                success:function (data) {
                                    vm.discussions = data.data.data; // 这里对应后台数据的结构
                                    pageList(data.data,'http://localhost/discussion'); // 构造分页按钮列表
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({ el:"#discussion-list" });
            </script>
        </div>
        <div class="col-md-3">
            <div class="jumbotron forum-jumbotron text-center">
                @if(Auth::check())
                    <a class="btn btn-danger btn-lg" href="/discussion/create" role="button">发起新的讨论</a>
                @else
                    <a class="btn btn-danger btn-lg" href="/user/login" role="button">登陆参与讨论</a>
                @endif
            </div>
            {{--热点和推荐列表--}}
            <div id="hot-nice-list">
                <hot-nice-list></hot-nice-list>
            </div>
            <template id="template-hot-nice-list">
                <div>
                    <div class="panel panel-danger">
                        <div class="panel-heading text-center">
                            <h4>热门讨论</h4>
                        </div>
                        <div class="panel-body">
                            <div v-for="discussion in hot_discussions">
                                <a href="#" :href="['/discussion/show/'+discussion.id]"> @{{ discussion.title }} </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h4>推荐讨论</h4>
                        </div>
                        <div class="panel-body">
                            <div v-for="discussion in nice_discussions">
                                <a href="#" :href="['/discussion/show/'+discussion.id]"> @{{ discussion.title }} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('hot-nice-list',{
                    template:"#template-hot-nice-list",
                    data:function () {
                        return {
                            hot_discussions: '',
                            nice_discussions: ''
                        };
                    },
                    created:function () {
                        this.getHotDiscussions();
                        this.getNiceDiscussions();
                    },
                    methods:{
                        getHotDiscussions:function(){
                            var vm = this;
                            $.ajax({
                                type:'get',
                                url:'/api/discussion/getHotDiscussions',
                                dataType:'json',
                                success:function (data) {
                                    vm.hot_discussions = data.data;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        getNiceDiscussions:function () {
                            var vm = this;
                            $.ajax({
                                type:'get',
                                url:'/api/discussion/getNiceDiscussions',
                                dataType:'json',
                                success:function (data) {
                                    vm.nice_discussions = data.data;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({ el:"#hot-nice-list" });
            </script>
        </div>
    </div>
@stop