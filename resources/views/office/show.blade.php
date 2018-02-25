@extends('office.master')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="#" id="introduction-title"></a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <div id="introduction">
                <introduction></introduction>
            </div>
            <template id="template-introduction">
                <div>
                    <div class="jumbotron">
                        <div class="media">
                            <div class="media-left">
                                <img class="media-object img-circle img-avatar-middle" src="" :src="avatar" alt="username">
                            </div>
                            {{--主体内容--}}
                            <div class="media-body">
                                <h2 class="media-heading">@{{ introduction.title }}
                                    <a href="#" :href="['/user/center/info/'+introduction.user_id]"><span>@</span>@{{ username }}</a>
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
                        return {
                            introduction: '',
                            avatar: '',
                            username: ''
                        };
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
                                type:'GET',
                                url:'/office/getIntroduction/' + id,
                                dataType:'json',
                                success:function (data) {
                                    vm.introduction = data.introduction;
                                    vm.avatar = data.introduction.user_avatar[0].avatar;
                                    vm.username = data.introduction.username[0].username;
                                    $('#introduction-title').text(data.introduction.title);
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#introduction"
                });
            </script>
            {{--显示所有的新人报道回复--}}
            <div id="message-list">
                <message-list></message-list>
            </div>
            <template id="template-message-list">
                <div>
                    <div>
                        <div v-for="message in messages">
                            <hr>
                            <div class="media">
                                <div class="media-left">
                                    <img class="media-object img-circle img-avatar-small" src="" :src="message.user_avatar[0].avatar">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="#" :href="['/user/center/info/'+message.user_id]"><span>@</span>@{{message.username[0].username}}</a>
                                    </h4>
                                    <p>@{{message.body}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('message-list',{
                    template:"#template-message-list",
                    data:function () {
                        return {
                            messages: ''
                        };
                    },
                    created:function () {
                        this.getMessages();
                    },
                    methods:{
                        getMessages:function(){
                            var vm = this;
                            var href = location.href.split('/');
                            var id = href[href.length-1];
                            $.ajax({
                                type:'GET',
                                url:'/office/getMessages/' + id,
                                dataType:'json',
                                success:function (data) {
                                    vm.messages = data.messages.data;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#message-list"
                });
            </script>
            {{--创建新人报道回复--}}
            @if(Auth::check())
                <hr>
                @if(Auth::user()->username != $introduction->user->username)
                    <div>
                        <div class="form-group">
                            <label for="body">迎新内容：</label>
                            <textarea name="body" class="form-control" id="body" rows="10" style="resize: none"></textarea>
                        </div>
                        <div>
                            <button id="submit" class="btn btn-success form-control">打个招呼</button>
                        </div>
                        {{--可关闭的警告框--}}
                        <div class="master-alert">
                            <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
                        </div>
                    </div>
                @endif
            @else
                <hr>
                <a href="/user/login" class="form-control btn btn-success">登录参与迎新</a>
            @endif
            <script>
                $(document).ready(function () {
                    /* 提交按钮 */
                    $('#submit').on('click', function () {
                        var href = location.href.split('/'); // 获得地址栏地址并拆分
                        var id = href[href.length-1]; // 通过地址栏获得新人报道的 id
                        $('#submit').text('');
                        $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                        $.ajax({
                            type: 'post',
                            url: '/office/show/welcome',
                            data: {
                                'introduction_id': id,
                                'body': $('#body').val()
                            },
                            dataType: 'json',
                            success: function (data) {
                                $('#submit').empty();
                                $('#submit').text('打个招呼');
                                if(data.status == 1){
                                    window.location.href = "/office/show/"+ data.introduction_id;
                                } else if(data.status == 0){
                                    makeAlertBox('danger',data.message);
                                } else {
                                    makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
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
                    });
                });
            </script>
        </div>
    </div>
@stop