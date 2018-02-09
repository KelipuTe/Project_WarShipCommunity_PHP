<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WarShipCommunity</title>
    {{--引入需要用到的文件--}}
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/vue/2.4.4/vue.js"></script>
    @yield('head')
</head>
<body class="master-body">
{{--顶部导航条--}}
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
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    {{--如果用户已经登录，则显示与账号有关的信息--}}
                    {{--<li>
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
                    </li>--}}
                    <li><img src="{{Auth::user()->avatar}}" class="img-circle img-avatar-small" alt="50x50"></li>
                    <li>
                        <a href="" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{Auth::user()->username}}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a href="/user/userCenter">个人中心</a></li>
                            <li><a href="#">消息中心</a></li>
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
{{--顶部巨幕--}}
<div class="jumbotron master-top-jumbotron">
    <div class="container">
        <h2>Welcome to WarShipCommunity</h2>
    </div>
</div>
<div class="container container-size">
    {{--页面顶部快捷导航--}}
    <div class="row master-top">
        <div class="col-md-2">
            <a href="/welcome" class="btn btn-warning master-top-home"><span class="fa fa-home fa-lg"></span> 首页 </a>
        </div>
        <div class="col-md-6">
            <dl class="dt-row-by-3 text-center">
                <dt><a href="/office" class="btn btn-info"><span class="fa fa-book fa-lg"></span> 办公区 </a></dt>
                <dt><a href="/forum" class="btn btn-info"><span class="fa fa-commenting-o fa-lg"></span> 讨论区 </a></dt>
                <dt><a href="/activity" class="btn btn-info"><span class="fa fa-calendar fa-lg"></span> 活动区 </a></dt>
                <dt><a href="/spaceAdministration" class="btn btn-info"><span class="fa fa-rocket fa-lg"></span> 冷月航天局 </a></dt>
                <dt><a href="/factory" class="btn btn-info"><span class="fa fa-cog fa-lg"></span> 冷月制造厂 </a></dt>
                <dt><a href="/archives" class="btn btn-info"><span class="fa fa-archive fa-lg"></span> 冷月档案馆 </a></dt>
            </dl>
        </div>
        <div class="col-md-4">
            @if(Auth::check())
                <div class="master-top-user-login text-center">
                    <p><strong>欢迎回来，{{Auth::user()->username}}</strong></p>
                    <p><strong>您不在的这段时间，共收到 {{count(Auth::user()->unreadNotifications)}} 条消息</strong></p>
                </div>
            @else
                <div class="master-top-user-logout text-center">
                    <p><strong>尊敬的用户，您尚未<a href="/user/login" class="">登录</a></strong></p>
                </div>
            @endif
        </div>
        {{--顶部路径导航条--}}
        <div class="col-md-12">
            <ol class="breadcrumb">
                @section('breadCrumb')
                    <span>您现在的位置：</span><li><a href="/welcome">首页</a></li>
                @show{{--@show 定义并立即生成该区块--}}
            </ol>
        </div>
    </div>
    {{--页面主体部分--}}
    <div class="row master-middle">
        @yield('content')
    </div>
</div>
{{--页面底部--}}
<footer id="footer" class="footer master-footer">
    <hr>
    <div class="text-center">
        <a href="/about" class="alert-link">关 于</a>
    </div>
    <div class="text-center">
        <span class="fa fa-copyright fa-lg"></span>
        <span> 2017 </span>
        <span class="fa fa-heart fa-lg"></span>
        <span> KelipuTe </span>
        <span> | </span>
        <span> Powered by <a href="https://d.laravel-china.org/docs/5.5"> Laravel 5.5 </a></span>
    </div>
</footer>
</body>
</html>
