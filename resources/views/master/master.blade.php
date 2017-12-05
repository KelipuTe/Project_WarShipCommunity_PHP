<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WarShipCommunity</title>
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/vue/2.4.4/vue.js"></script>
    @yield('head')
</head>
<body class="master-body">
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
                    <li>
                        <a href="" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">消 息
                            @if(count(Auth::user()->unreadNotifications))
                                <span class="badge">{{count(Auth::user()->unreadNotifications)}}</span>
                            @endif
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a href="/notification/showAll">所有消息</a></li>
                            <li><a href="/notification/showUnread">未读消息
                                    @if(count(Auth::user()->unreadNotifications))
                                        <span class="badge">{{count(Auth::user()->unreadNotifications)}}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li><img src="{{Auth::user()->avatar}}" class="img-circle img_avatar_small" alt="50x50"></li>
                    <li>
                        <a href="" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{Auth::user()->username}}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a href="/user/infoEdit">个人信息</a></li>
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
<div class="jumbotron master-top-jumbotron">
    <div class="container">
        <h2>Welcome to WarShipCommunity</h2>
    </div>
</div>
<div class="container master-top">
    <div class="row">
        <!-- 顶部胶囊式标签页 -->
        <div>
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a class="btn-primary">快捷导航</a></li>
                <li role="presentation"><a href="/office" class="btn-primary">办公区</a></li>
                <li role="presentation"><a href="/forum" class="btn-primary">讨论区</a></li>
                <li role="presentation"><a href="/activity" class="btn-primary">活动区</a></li>
                <li role="presentation"><a href="/spaceAdministration" class="btn-primary">冷月航天局</a></li>
                <li role="presentation"><a href="/factory" class="btn-primary">冷月制造厂</a></li>
                <li role="presentation"><a href="/archives" class="btn-primary">冷月档案馆</a></li>
            </ul>
        </div>
        <!-- 顶部路径导航条 -->
        <div>
            <ol class="breadcrumb">
                @section('breadCrumb')
                <span>您现在的位置：</span><li><a href="/welcome">首页</a></li>
                @show
            </ol>
        </div>
    </div>
</div>
<!-- 页面主体部分 -->
<div class="container master-middle">
    @yield('content')
</div>
<!-- 页面底部 -->
<footer id="footer" class="footer master-footer">
    <hr>
    <div class="text-center">
        <span> &copy; </span><span> 2017 </span><span class="glyphicon glyphicon-heart"></span><span> KelipuTe </span>
        <span> | </span>
        <span> Powered by <a href="https://d.laravel-china.org/docs/5.5"> Laravel 5.5 </a></span>
    </div>
</footer>
</body>
</html>
