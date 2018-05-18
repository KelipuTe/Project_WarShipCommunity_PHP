<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::check() ? 'Bearer '.Auth::user()->api_token : 'Bearer ' }}">
    <title>WarShipCommunity</title>
    {{--引入需要用到的文件--}}
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/vue/2.4.4/vue.js"></script>
    <script src="http://{{Request::getHost()}}:6001/socket.io/socket.io.js"></script>
    <script type="text/javascript" rel="script" src="/js/app.js"></script>
    <script>
        /*
         * 别放在 $(document).ready(function () {}) 里面
         * 别放在页面底部
         * 这个语句需要在所有的 ajax 请求发起之前执行
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
                'Authorization':$('meta[name="api-token"]').attr('content')
            }
        })
    </script>
    @yield('head')
</head>
<body class="master-body">
{{--顶部导航条--}}
<div id="nav-top">
    <nav-top></nav-top>
</div>
<template id="template-nav-top">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/welcome">WarShipCommunity</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/welcome">首 页</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right" v-if="user!=false">
                    <li><img src="" :src="user.avatar" class="img-circle img-avatar-small" alt="50x50"></li>
                    <li>
                        <a href="" id="userDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            @{{ user.username }}<span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdownMenu">
                            <li><a href="/user/center">个人中心</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/user/logout">退出登录</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="" id="notificationsDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            消息 <span class="badge">@{{ countUnreadNotifications }}</span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="notificationsDropdownMenu">
                            <li><a href="/notification/center">消息中心</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right" v-else="user!=false">
                    <li><a href="/user/login">登 录</a></li>
                    <li><a href="/user/register">注 册</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</template>
<script>
    Vue.component('nav-top',{
        template:"#template-nav-top",
        data:function () {
            return {
                user: '',
                unreadNotifications: '',
                countUnreadNotifications: 0
            }
        },
        created:function () {
            this.init();
        },
        methods:{
            init:function(){
                var vm = this;
                $.ajax({
                    type:'get',
                    url:'/master/getUser',
                    dataType:'json',
                    success:function (data) {
                        if(data.user != false){
                            vm.user = data.user;
                            vm.unreadNotifications = data.unreadNotifications;
                            vm.countUnreadNotifications = vm.unreadNotifications.length;
                            window.Echo.private('broadcast-notification-' + vm.user.id)
                                .listen('BroadcastNotification',e =>{
                                    vm.unreadNotifications.push($.parseJSON(e.notification));
                                    vm.countUnreadNotifications = vm.unreadNotifications.length;
                                    console.log(vm.countUnreadNotifications);
                                });
                        }
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            }
        }
    });
    new Vue({ el:"#nav-top" });
</script>
{{--顶部巨幕--}}
<div class="jumbotron master-top-jumbotron">
    <div class="container">
        <h2>Welcome to WarShipCommunity</h2>
    </div>
</div>
{{--页面中部--}}
<div class="container container-size">
    {{--页面顶部快捷导航--}}
    <div class="row master-top">
        <div class="col-md-2">
            <a href="/welcome" class="btn btn-warning master-top-home"><span class="fa fa-home fa-lg"></span>首页</a>
        </div>
        <div class="col-md-6">
            <dl class="dt-row-by-3 text-center">
                <dt><a href="/office" class="btn btn-info"><span class="fa fa-book fa-lg"></span> 办公区</a></dt>
                <dt><a href="/discussion" class="btn btn-info"><span class="fa fa-commenting-o fa-lg"></span> 讨论区</a></dt>
                <dt><a href="/activity" class="btn btn-info"><span class="fa fa-calendar fa-lg"></span> 活动区</a></dt>
                <dt><a href="/spaceAdministration" class="btn btn-info"><span class="fa fa-rocket fa-lg"></span> 冷月航天局</a></dt>
                <dt><a href="/factory" class="btn btn-info"><span class="fa fa-cog fa-lg"></span> 冷月制造厂</a></dt>
                <dt><a href="/archives" class="btn btn-info"><span class="fa fa-archive fa-lg"></span> 冷月档案馆</a></dt>
            </dl>
        </div>
        <div class="col-md-4"></div>
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
    {{--可关闭的警告框--}}
    <div class="master-alert" style="z-index: 999">
        <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
    </div>
</div>
{{--页面底部--}}
<footer id="footer" class="footer master-footer">
    <hr>
    <div class="text-center">
        <a href="/about" class="alert-link">关 于</a>
    </div>
    <div class="text-center">
        <span class="fa fa-copyright fa-lg"></span><span> 2017-2018 </span>
        <span class="fa fa-heart fa-lg"></span><span> KelipuTe </span><span> | </span>
        <span> Powered by <a href="https://d.laravel-china.org/docs/5.5"> Laravel 5.5 </a></span>
    </div>
</footer>
<script>
    /*
     * 生成警告框
     * type 警告框类型
     * value 警告框提示信息
     *
     * 在页面中需要如下结构
     * <div class="master-alert">
     *     <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
     * </div>
     */
    function makeAlertBox(type,value){
        var title;
        switch (type) {
            case 'success' :
                title = '成功！'; break;
            case 'info' :
                title = '信息！'; break;
            case 'warning' :
                title = '警告！'; break;
            case 'danger' :
                title = '错误！'; break;
            default :
                title = '错误！';
        }
        $('#master-alert-container').append(
            '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<strong>' + title + '</strong>' + value +
            '</div>');
    }

    /*
     * 构造分页按钮列表
     * data 分页数据
     * page_url_before 地址前缀
     *
     * 在页面中需要如下结构
     * <div class="text-center">
     *     <ul id="page-list" class="pagination"></ul>
     * </div>
     */
    function pageList (data,page_url_before) {
        if(data.total != 0) {
            // 构造当前位置
            $('#page-list').append('<li class="active"><span>' + data.current_page + '</span></li>');
            var page_url = page_url_before;
            // 向前构造分页按钮
            if (data.current_page > 1) {
                for (var page = data.current_page - 1; page >= 1; --page) {
                    page_url = page_url_before + '?page=' + page;
                    $('#page-list').prepend('<li><a href="' + page_url + '">' + page + '</a></li>');
                }
            }
            // 构造上一页按钮
            if (data.current_page == 1) {
                $('#page-list').prepend('<li class="disabled"><span>«</span></li>');
            } else {
                page_url = page_url_before + '?page=' + (data.current_page - 1);
                $('#page-list').prepend('<li><a href="' + page_url + '" rel="prev">«</a></li>');
            }
            // 向后构造分页按钮
            if (data.current_page < data.last_page) {
                for (var page = data.current_page + 1; page <= data.last_page; ++page) {
                    page_url = page_url_before + '?page=' + page;
                    $('#page-list').append('<li><a href="' + page_url + '">' + page + '</a></li>');
                }
            }
            // 构造下一页按钮
            if (data.current_page == data.last_page) {
                $('#page-list').append('<li class="disabled"><span>»</span></li>');
            } else {
                page_url = page_url_before + '?page=' + (data.current_page + 1);
                $('#page-list').append('<li><a href="' + page_url + '" rel="next">»</a></li>');
            }
        }
    }
</script>
</body>
</html>
