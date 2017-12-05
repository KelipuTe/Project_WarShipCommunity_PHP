@extends('user.app')
@section('breadCrumb')
    @parent
    <li><a href="/user/login">登录</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-3 text-center"><h1 id="left_OvO" class="user-login-eye-size">0</h1></div>
        <div class="col-md-6" role="main">
            @include('error.errorList')
            @if(Session::has('user_login_failed'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('user_login_failed')}}
                </div>
            @endif
            @if(Session::has('user_register_success'))
                <div class="alert alert-danger" role="alert">
                    {{Session::get('user_register_success')}}
                </div>
            @endif
            {!! Form::open(['url'=>'/user/signIn']) !!}
            <div class="form-group">
                {!! Form::label('email','Email:') !!}
                {!! Form::email('email',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password','Password:') !!}
                {!! Form::password('password',['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <input type="text" class="form-control user-login-line"/>
                </div>
                <div class="col-md-3">
                    <canvas id="canvas" width="120" height="50"></canvas>
                </div>
                <div class="col-md-3">
                    <a href="#" id="changeImg" class="btn btn-sm btn-warning user-login-line">看不清，换一张</a>
                </div>
            </div>
            <script>
                /*生成一个随机数*/
                function randomNum(min,max){
                    return Math.floor( Math.random()*(max-min)+min);
                }
                /*生成一个随机色*/
                function randomColor(min,max){
                    var r = randomNum(min,max);
                    var g = randomNum(min,max);
                    var b = randomNum(min,max);
                    return "rgb("+r+","+g+","+b+")";
                }
                /*绘制验证码图片*/
                function drawPic(){
                    var canvas=document.getElementById("canvas");
                    var width=canvas.width;
                    var height=canvas.height;
                    var ctx = canvas.getContext('2d');
                    ctx.textBaseline = 'bottom';
                    /*绘制背景色*/
                    ctx.fillStyle = randomColor(180,240); //颜色若太深可能导致看不清
                    ctx.fillRect(0,0,width,height);
                    /*绘制文字*/
                    var str = 'ABCEFGHJKLMNPQRSTWXY123456789';
                    var i;
                    for(i=0; i<4; i++){
                        var txt = str[randomNum(0,str.length)];
                        console.log(txt);
                        ctx.fillStyle = randomColor(50,160);  //随机生成字体颜色
                        ctx.font = randomNum(20,30)+'px SimHei'; //随机生成字体大小
                        var x = 10+i*25;
                        var y = randomNum(25,45);
                        var deg = randomNum(-45, 45);
                        //修改坐标原点和旋转角度
                        ctx.translate(x,y);
                        ctx.rotate(deg*Math.PI/180);
                        ctx.fillText(txt, 0,0);
                        //恢复坐标原点和旋转角度
                        ctx.rotate(-deg*Math.PI/180);
                        ctx.translate(-x,-y);
                    }
                    /*绘制干扰线*/
                    for(i=0; i<8; i++){
                        ctx.strokeStyle = randomColor(40,180);
                        ctx.beginPath();
                        ctx.moveTo( randomNum(0,width), randomNum(0,height) );
                        ctx.lineTo( randomNum(0,width), randomNum(0,height) );
                        ctx.stroke();
                    }
                    /*绘制干扰点*/
                    for(i=0; i<100; i++){
                        ctx.fillStyle = randomColor(0,255);
                        ctx.beginPath();
                        ctx.arc(randomNum(0,width),randomNum(0,height), 1, 0, 2*Math.PI);
                        ctx.fill();
                    }
                }
                drawPic();
                document.getElementById("changeImg").onclick = function(e){
                    e.preventDefault();
                    drawPic();
                };
            </script>
            {!! Form::submit('登 录',['class'=>'btn btn-primary form-control user-login-btn']) !!}
            {!! Form::close() !!}
        </div>
        <div class="col-md-3 text-center"><h1 id="right_OvO" class="user-login-eye-size">0</h1></div>
        <script>
            $(document).ready(function () {
                $('#password').click(function () {
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
            });
        </script>
    </div>
@stop