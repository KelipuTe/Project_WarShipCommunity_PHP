@extends('user.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/user/register">注册</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-3 text-center">
            <h1 id="left_OvO" class="user-login-eye-size">0</h1>
        </div>
        <div class="col-md-6" role="main">
            <div>
                <input id="token" name="_token" value="{{csrf_token()}}" type="hidden"> {{--CSRF--}}
                <div class="form-group input-group user-login-line">
                    <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                    <input id="email" name="email" class="form-control" type="text" placeholder="请输入登录账号">
                </div>
                <div class="form-group input-group user-login-line">
                    <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                    <input id="password" name="password" class="form-control" type="password" placeholder="请输入登录密码">
                </div>
                <div class="form-group input-group user-login-line">
                    <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                    <input id="password_confirmation" name="password_confirmation" class="form-control" type="password" placeholder="请确认登录密码">
                </div>
                <div class="form-group input-group user-login-line">
                    <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
                    <input id="username" name="username" class="form-control" type="text" placeholder="请输入用户名">
                </div>
                <div class="form-group">
                    <button id="submit" class="btn btn-success form-control user-login-line">注册</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <h1 id="right_OvO" class="user-login-eye-size">0</h1>
        </div>
        {{--可关闭的警告框--}}
        <div class="master-alert">
            <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            /* 添加 CSRF 保护 */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                }
            });

            /* 提交按钮 */
            $('#submit').on('click', function () {
                $('#submit').text('');
                $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                $.ajax({
                    type: 'post',
                    url: '/user/create',
                    data: {
                        'email': $('#email').val(),
                        'password': $('#password').val(),
                        'password_confirmation': $('#password_confirmation').val(),
                        'username': $('#username').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#submit').empty();
                        $('#submit').text('注册');
                        if(data.status == 1){
                            makeAlertBox('success',data.message);
                        } else if(data.status == 0){
                            makeAlertBox('danger',data.message);
                        } else {
                            makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                        }
                    },
                    error: function (jqXHR) {
                        $('#submit').empty();
                        $('#submit').text('注册');
                        if(jqXHR.status == 422){
                            // 遍历被 Laravel Request 拦截后返回的错误提示
                            $.each(jqXHR.responseJSON.errors,function (index,value) {
                                makeAlertBox('danger',value);
                            });
                        } else if (jqXHR.status == 500){
                            makeAlertBox('danger','很抱歉，服务器正忙，请稍后再试！！！');
                        }
                    }
                });
            });
        });

        /*
         * 生成警告框
         * type 警告框类型
         * value 警告框提示信息
         */
        function makeAlertBox(type,value){
            var title;
            switch (type) {
                case 'success' :
                    title = '成功！';
                    break;
                case 'info' :
                    title = '信息！';
                    break;
                case 'warning' :
                    title = '警告！';
                    break;
                case 'danger' :
                    title = '错误！';
                    break;
                default :
                    title = '错误！';
            }
            $('#master-alert-container').append(
                '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong>' + title + '</strong>' + value +
                '</div>');
        }

        /* 控制输入密码时两侧眼睛变化 */
        $('#password').focus(function () {
            $('#left_OvO').text('︶');
            $('#left_OvO').addClass('user-login-eye');
            $('#right_OvO').text('︶');
            $('#right_OvO').addClass('user-login-eye');
        });
        $('#password').blur(function () {
            $('#left_OvO').text('0');
            $('#left_OvO').removeClass('user-login-eye');
            $('#right_OvO').text('0');
            $('#right_OvO').removeClass('user-login-eye');
        });
        $('#password_confirmation').focus(function () {
            $('#left_OvO').text('︶');
            $('#left_OvO').addClass('user-login-eye');
            $('#right_OvO').text('︶');
            $('#right_OvO').addClass('user-login-eye');
        });
        $('#password_confirmation').blur(function () {
            $('#left_OvO').text('0');
            $('#left_OvO').removeClass('user-login-eye');
            $('#right_OvO').text('0');
            $('#right_OvO').removeClass('user-login-eye');
        });
    </script>
@stop