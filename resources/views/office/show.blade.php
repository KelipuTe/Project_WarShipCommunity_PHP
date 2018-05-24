@extends('office.app')
@section('breadCrumb')
    @parent
    <li><a href="#" id="introduction-title"></a></li>
@stop
@section('content')
    <div class="col-md-10 col-md-offset-1">
        <div id="introduction">
            <introduction></introduction>
        </div>
        <template id="template-introduction">
            <div>
                <div class="jumbotron">
                    <div class="media">
                        <div class="media-left">
                            <img src="" :src="introduction.relatedInfo.avatar"
                                 class="media-object img-circle img-avatar-middle" alt="avatar:150x150">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading">@{{ introduction.title }}
                                <a href="#" :href="['/user/center/info/'+introduction.user_id]">
                                    <span>@</span>@{{ introduction.relatedInfo.username }}
                                </a>
                            </h2>
                            <h3>@{{ introduction.body }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <script>
            Vue.component('introduction',{
                template:"#template-introduction",
                data:function () {
                    return { introduction: '' };
                },
                created:function () {
                    this.getIntroduction();
                },
                methods:{
                    getIntroduction:function(){
                        var vm = this;
                        var href = location.href.split('/');
                        var id = href[href.length-1];
                        $.ajax({
                            type:'get',
                            url:'/office/getIntroduction/' + id,
                            dataType:'json',
                            success:function (data) {
                                vm.introduction = data.introduction;
                                $('#introduction-title').text(data.introduction.title);
                            }
                        });
                    }
                }
            });
            new Vue({ el:"#introduction" });
        </script>
        {{--显示所有的新人报道回复--}}
        <div id="message-list">
            <message-list></message-list>
        </div>
        <template id="template-message-list">
            <div>
                <div v-for="message in messages">
                    <hr>
                    <div class="media">
                        <div class="media-left">
                            <img src="" :src="message.relatedInfo.avatar"
                                 class="media-object img-circle img-avatar-small" alt="avatar:50x50">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="#" :href="['/user/center/info/'+message.user_id]">
                                    <span>@</span>@{{message.relatedInfo.username}}
                                </a>
                            </h4>
                            <p>@{{message.body}}</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <ul id="page-list" class="pagination"></ul>
                </div>
                {{--创建新人报道回复--}}
                <div v-if="isLogin">
                    <hr>
                    <div class="form-group">
                        <label for="body">迎新内容：</label>
                        <textarea id="message-body" class="form-control" rows="5" style="resize: none"></textarea>
                    </div>
                    <div class="form-group">
                        <button id="submit" class="btn btn-success form-control" @click="messageStore">打个招呼</button>
                    </div>
                </div>
                <div v-else="isLogin">
                    <hr>
                    <a href="/user/login" class="form-control btn btn-success">登录参与迎新</a>
                </div>
            </div>
        </template>
        <script>
            Vue.component('message-list',{
                template:"#template-message-list",
                data:function () {
                    return {
                        messages: '',
                        isLogin: false
                    };
                },
                created:function () {
                    if($.trim($('meta[name="api-token"]').attr('content')) != 'Bearer'){
                        this.isLogin = true;
                    }
                    this.getMessages();
                },
                methods:{
                    getMessages:function(){
                        var vm = this;
                        var href = location.href.split('/');
                        var id = href[href.length-1];
                        $.ajax({
                            type:'get',
                            url:'/office/getMessages/' + id,
                            dataType:'json',
                            success:function (data) {
                                vm.messages = data.messages.data;
                                pageList(data.messages,'http://localhost/discussion'); // 构造分页按钮列表
                            }
                        });
                    },
                    messageStore:function(){
                        var vm = this;
                        var href = location.href.split('/');
                        var id = href[href.length-1];
                        $('#submit').text('');
                        $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                        $.ajax({
                            type: 'post',
                            url: '/office/messageStore',
                            data: {
                                'introduction_id': id,
                                'body': $('#message-body').val()
                            },
                            dataType: 'json',
                            success: function (data) {
                                $('#submit').empty();
                                $('#submit').text('打个招呼');
                                if(data.status == 1){
                                    $('#message-body').val('');
                                    $('#page-list').empty();
                                    vm.getMessages();
                                } else {
                                    makeAlertBox('danger',data.message);
                                }
                            },
                            error: function (jqXHR) {
                                $('#submit').empty();
                                $('#submit').text('打个招呼');
                                if(jqXHR.status == 422){
                                    $.each(jqXHR.responseJSON.errors,function (index,value) {
                                        makeAlertBox('danger',value);
                                    });
                                }
                            }
                        });
                    }
                }
            });
            new Vue({ el:"#message-list" });
        </script>
    </div>
@stop