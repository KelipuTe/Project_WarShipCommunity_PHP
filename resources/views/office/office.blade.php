@extends('office.master')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            {{--BootStrap 标签页--}}
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#introduction" aria-controls="introduction" role="tab" data-toggle="tab">新人报道</a>
                </li>
                <li role="presentation">
                    <a href="#violations" aria-controls="violations" role="tab" data-toggle="tab">小黑屋</a>
                </li>
                <li role="presentation">
                    <a href="#office" aria-controls="office" role="tab" data-toggle="tab">办公室</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="introduction">
                    <div class="row">
                        {{--显示新人报道列表--}}
                        <div class="col-md-10 col-md-offset-1" role="main">
                            <div id="introduction-list">
                                <introduction-list></introduction-list>
                            </div>
                            <div class="text-center">
                                <ul id="page-list" class="pagination"></ul>
                            </div>
                            <template id="template-introduction-list">
                                <div>
                                    <div v-for="introduction in introductions">
                                        <div class="media office-line">
                                            <div class="media-left">
                                                <img class="media-object img-circle img-avatar-small" src="" :src="introduction.user_avatar[0].avatar">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <a href="#" :href="['/office/show/'+introduction.id]">@{{ introduction.title }}</a>
                                                </h4>
                                                @{{ introduction.username[0].username }}
                                                <div class="pull-right">
                                                    <span> 共 @{{ introduction.count_messages }} 条回复 </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <script>
                                Vue.component('introduction-list',{
                                    template:"#template-introduction-list",
                                    data:function () {
                                        return {
                                            introductions:''
                                        };
                                    },
                                    created:function () {
                                        this.getIntroductions();
                                    },
                                    methods:{
                                        getIntroductions:function(){
                                            var vm = this;
                                            var url = '/office/getIntroductions';
                                            var href = location.href; // 获得地址栏地址
                                            if( href.indexOf('?') != -1 ){
                                                //判断是不是翻页后的地址，携带 ?page=number
                                                href = location.href.split('=');
                                                url = '/office/getIntroductions?page='+ href[href.length-1];
                                            }
                                            $.ajax({
                                                type:'GET',
                                                url:url,
                                                dataType:'json',
                                                success:function (data) {
                                                    vm.introductions = data.introductions.data;
                                                    pageList(data.introductions,'http://localhost/office'); // 构造分页按钮列表
                                                },
                                                error:function(jqXHR){
                                                    console.log("出现错误：" +jqXHR.status);
                                                }
                                            });
                                        }
                                    }
                                });
                                new Vue({
                                    el:"#introduction-list"
                                });
                            </script>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="/office/create" class="form-control btn btn-success">我要报道</a>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="violations">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img alt="200 * 200" src="/image/avatar/default_avatar.jpg" style="height: 200px; width: 200px; display: block;">
                                <div class="caption">
                                    <h3 class="text-center">
                                        <a href="/office/blacklist/notice" class="btn btn-primary"> 公告牌 </a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img alt="200 * 200" src="/image/avatar/default_avatar.jpg" style="height: 200px; width: 200px; display: block;">
                                <div class="caption">
                                    <h3 class="text-center">
                                        <a href="/office/blacklist/archives" class="btn btn-primary"> 档案室 </a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="office">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <img alt="200 * 200" src="/image/avatar/default_avatar.jpg" style="height: 200px; width: 200px; display: block;">
                                <div class="caption">
                                    <h3 class="text-center">
                                        <a href="/office/warship" class="btn btn-primary">舰船信息管理中心</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop