@extends('user.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/user/login">登录</a></li>
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
                <div class="form-group text-center user-login-line">
                    <div class="col-md-6">
                        <input id="captcha" name="captcha" type="text" class="form-control user-login-line-captcha" placeholder="请输入右侧验证码"/>
                        <input id="captcha_code" name="captcha_code" type="hidden"/>
                    </div>
                    <div class="col-md-3">
                        <canvas id="canvas" width="120" height="50"></canvas>
                    </div>
                    <div class="col-md-3">
                        <a href="#" id="changeImg" class="btn btn-sm btn-warning user-login-line-captcha">看不清，换一张</a>
                    </div>
                </div>
                <div class="form-group">
                    <button id="submit" class="btn btn-success form-control user-login-line">登录</button>
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
            var captcha_code; // 记录验证码
            captcha_code = drawPic(); // 绘制验证码
            $('#captcha_code').val(captcha_code);

            /* 点击按钮，重新绘制验证码 */
            $('#changeImg').on('click', function (e) {
                e.preventDefault();
                captcha_code = drawPic();
                $('#captcha_code').val(captcha_code);
            });

            /* 添加 CSRF 保护 */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                }
            });

            /* 提交按钮 */
            $('#submit').on('click', function () {
                if( $('#captcha').val() == captcha_code){
                    $('#submit').text('');
                    $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                    $.ajax({
                        type: 'post',
                        url: '/user/signIn',
                        data: {
                            'email': $('#email').val(),
                            'password': $('#password').val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            $('#submit').empty();
                            $('#submit').text('登录');
                            if(data.status == 1){
                                // 登陆成功
                                window.location.href = "/welcome";
                            } else if(data.status == -1){
                                // 账号未激活
                                makeAlertBox('info',data.message,'reSendEmailConfirm');
                            } else if(data.status == 0){
                                makeAlertBox('danger',data.message,'');
                            } else {
                                makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！','');
                            }
                        },
                        error: function (jqXHR) {
                            $('#submit').empty();
                            $('#submit').text('登录');
                            if(jqXHR.status == 422){
                                // 遍历被 Laravel Request 拦截后返回的错误提示
                                $.each(jqXHR.responseJSON.errors,function (index,value) {
                                    makeAlertBox('danger',value,'');
                                });
                            }
                        }
                    });
                } else {
                    makeAlertBox('danger','验证码错误！！！','');
                }
            });
        });

        /*
         * 生成警告框
         * type 警告框类型
         * value 警告框提示信息
         * link 警告框链接
         */
        function makeAlertBox(type,value,link){
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
            if(link != null && link != ''){
                $('#master-alert-container').append(
                    '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<strong>' + title + '</strong>' + value + '<a id="reSendEmailConfirm" href="#" class="alert-link">点击重新发送邮箱验证邮件</a>' +
                    '</div>');
                $('#reSendEmailConfirm').on('click',function () {
                    if($('#email').val() != null && $('#email').val() != '') {
                        $.ajax({
                            type: 'get',
                            url: '/user/reSendEmailConfirm/' + $('#email').val(),
                            dataType: 'json',
                            success: function (data) {
                                if(data.status == 1){
                                    makeAlertBox('success',data.message);
                                } else if(data.status == 0){
                                    makeAlertBox('danger',data.message);
                                } else {
                                    makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                }
                            },
                            error: function (jqXHR) {
                                if (jqXHR.status == 500){
                                    makeAlertBox('danger','很抱歉，服务器正忙，请稍后再试！！！');
                                }
                            }
                        });
                    }
                });
            } else {
                $('#master-alert-container').append(
                    '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<strong>' + title + '</strong>' + value +
                    '</div>');
            }
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

        /* 生成一个随机数 */
        function randomNum(min,max){
            return Math.floor( Math.random()*(max-min)+min);
        }
        /* 生成一个随机色 */
        function randomColor(min,max){
            var r = randomNum(min,max);
            var g = randomNum(min,max);
            var b = randomNum(min,max);
            return "rgb("+r+","+g+","+b+")";
        }
        /* 绘制验证码图片 */
        function drawPic(){
            var canvas=document.getElementById("canvas");
            var width=canvas.width;
            var height=canvas.height;
            var ctx = canvas.getContext('2d');
            ctx.textBaseline = 'bottom';
            /* 绘制背景色 */
            ctx.fillStyle = randomColor(180,240); // 颜色若太深可能导致看不清
            ctx.fillRect(0,0,width,height);
            /* 绘制文字 */
            var str = 'abcdefghijklmnopqrstuvwxyz123456789';
            var i;
            var captcha_code = '';
            for(i=0; i<4; i++){
                var txt = str[randomNum(0,str.length)];
                //console.log(txt);
                captcha_code += txt;
                ctx.fillStyle = randomColor(50,160);  // 随机生成字体颜色
                ctx.font = randomNum(20,30)+'px SimHei'; // 随机生成字体大小
                var x = 10+i*25;
                var y = randomNum(25,45);
                var deg = randomNum(-45, 45);
                // 修改坐标原点和旋转角度
                ctx.translate(x,y);
                ctx.rotate(deg*Math.PI/180);
                ctx.fillText(txt, 0,0);
                // 恢复坐标原点和旋转角度
                ctx.rotate(-deg*Math.PI/180);
                ctx.translate(-x,-y);
            }
            /* 绘制干扰线 */
            for(i=0; i<8; i++){
                ctx.strokeStyle = randomColor(40,180);
                ctx.beginPath();
                ctx.moveTo( randomNum(0,width), randomNum(0,height) );
                ctx.lineTo( randomNum(0,width), randomNum(0,height) );
                ctx.stroke();
            }
            /* 绘制干扰点 */
            for(i=0; i<100; i++){
                ctx.fillStyle = randomColor(0,255);
                ctx.beginPath();
                ctx.arc(randomNum(0,width),randomNum(0,height), 1, 0, 2*Math.PI);
                ctx.fill();
            }
            return captcha_code;
        }
    </script>
@stop