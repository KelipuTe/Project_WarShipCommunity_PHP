<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WarShipCommunity</title>
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
    <script type="text/javascript" src="/js/ThirdPartyLibrary/jquery.form.js"></script>
</head>
<body>
<!-- 顶部导航条 -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/welcome">WarShipCommunity</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/welcome">首 页</a></li>
                <li><a href="/about">关 于</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li><img src="{{Auth::user()->avatar}}" class="img-circle img_avatar" alt="50x50"></li>
                    <li>
                        <a href="" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{Auth::user()->username}}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a href="#">个人信息</a></li>
                            <li><a href="#">暂无内容</a></li>
                            <li><a href="#">暂无内容</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/user/logout">退出登录</a></li>
                        </ul>
                    </li>
                @else
                    <li><a href="/user/login">登 录</a></li>
                    <li><a href="/user/register">注 册</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!-- 顶部巨幕-->
<div class="jumbotron">
    <div class="container">
        <h2>Welcome to WarShipCommunity</h2>
    </div>
</div>
<div class="container">
    <div class="row">
        <!-- 顶部胶囊式标签页 -->
        <div>
            <ul class="nav nav-pills">
                <li role="presentation"></li>
                <li role="presentation" class="active"><a class="btn-primary">快捷导航</a></li>
                <li role="presentation"><a href="/office">办公区</a></li>
                <li role="presentation"><a href="/forum">讨论区</a></li>
                <li role="presentation"><a href="/activity">活动区</a></li>
                <li role="presentation"><a href="/spaceAdministration">冷月航天局</a></li>
                <li role="presentation"><a href="/factory">冷月制造厂</a></li>
                <li role="presentation"><a href="/archives">冷月档案馆</a></li>
            </ul>
        </div>
        <!-- 顶部路径导航条 -->
        <div>
            <ol class="breadcrumb">
                @section('breadCrumb')
                <li><a href="/welcome">主页</a></li>
                @show
            </ol>
        </div>
    </div>
</div>
<!-- 页面主体部分 -->
@yield('content')
<!-- 页面底部 -->
<footer id="footer" class="footer">
    <hr>
    <div class="text-center">
        <span> &copy; </span><span> 2017 </span><span class="glyphicon glyphicon-heart"></span><span> KelipuTe </span>
        <span> | </span>
        <span> Powered by <a href="https://laravel-china.org/"> Laravel </a></span>
    </div>
</footer>
</body>
</html>
