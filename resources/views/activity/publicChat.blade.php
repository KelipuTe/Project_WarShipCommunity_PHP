@extends('activity.app')
@section('breadCrumb')
    @parent
    <li><a href="">公共聊天室</a></li>
@stop
@section('content')
    <script src="https://cdn.bootcss.com/socket.io/2.0.3/socket.io.slim.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" role="main">
                <div>
                    <dl id="publicChatRoom"></dl>
                </div>
                @if(Auth::check())
                    @include('error.errorList')
                    {!! Form::open(['url'=>'/activity/showPublicChat','id'=>'chat_form']) !!}
                    <div class="form-group">
                        <input type="text" id="username" name="username" value="{{\Auth::user()->username}}" style="display:none">
                        <input type="text" id="time" name="time" value="{{\Carbon\Carbon::now()}}" style="display:none">
                    </div>
                    <div class="form-group">
                        {!! Form::textarea('body',null,['id'=>'body','class'=>'form-control']) !!}
                    </div>
                    {!! Form::close() !!}
                    <div>
                        <button id="chat_submit" class="form-control btn btn-primary">发送</button>
                    </div>
                @else
                    <a class="form-control btn btn-success" href="/user/login" role="button">登陆参与讨论</a>
                @endif
            </div>
        </div>
    </div>
    <script>
        /*var socket = io('127.0.0.1:3000');
        socket.on('public-channel:publicChat',function (data) {
            $('#publicChatRoom').append($('<dt>').text(data.username));
            $('#publicChatRoom').append($('<dt>').text(data.time));
            $('#publicChatRoom').append($('<dt>').text(data.chatMessage));
        });*/

        var socket = io('172.17.93.1:3000');
        socket.on('public-channel:publicChat',function (data) {
            $('#publicChatRoom').append($('<dt>').text(data.username));
            $('#publicChatRoom').append($('<dt>').text(data.time));
            $('#publicChatRoom').append($('<dt>').text(data.chatMessage));
        });

        $(document).ready(function () {
            $('#chat_submit').on('click',function () {
                /*$('#publicChatRoom').append($('<dt>').text($('#username').val()));
                $('#publicChatRoom').append($('<dt>').text($('#time').val()));
                $('#publicChatRoom').append($('<dt>').text($('#body').val()));*/
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('#chat_form input[name="_token"]').val()
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/activity/showPublicChat',
                    data: {
                        "username": $('#username').val(),
                        "time": $('#time').val(),
                        "body": $('#body').val()
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log("success：" +data);
                    },
                    error: function(jqXHR){
                        console.log("error：" +jqXHR.status);
                    }
                });
            });
        });
    </script>
@stop