@extends('archives.app')
@section('content')
    <style type="text/css">
        .slipping-master{
            margin: 0 auto;
        }
        .slipping-show {
            width:100px;
            height:70px;
            overflow:hidden;
            position:relative;
            float:left;
        }
        .slipping-main {
            width:100px;
            height:70px;
            position:relative;
            float:left;
        }
        .slipping-box {
            width:100px;
            height:70px;
            float:left;
        }
    </style>

    <div id="slipping-container-master" class="slipping-master"></div>

    <script type="text/javascript">
        var mainWidth = 100; // 图片宽度
        var x; // 图片横向坐标
        var y; // 图片纵向坐标
        var MAX_X = 6; // 图片横向最大行数
        var MAX_Y = 8; // 图片纵向最大列数
        var totalWidth = MAX_Y * mainWidth; // 载体横向最大宽度
        $("#slipping-container-master").css('width',totalWidth);
        for(x = 1;x <= MAX_X ;++x){
            /* 在修改过最大规格后，需要手动调整载体容器宽度 */
            $("#slipping-container-master").append('<div id="slipping-container-'+x+'"></div>');
            $("#slipping-container-"+x).css('width',totalWidth);
            $("#slipping-container-"+x).css('height','70px');
            $("#slipping-container-"+x).hide(); // 隐藏横向元素，在添加纵向元素时保持隐藏
            for(y = 1;y <= MAX_Y;++y){
                $("#slipping-container-"+x).append("<div class='slipping-show slipping-show-"+x+y+"'></div>"); // 添加观察窗口元素
                $(".slipping-show-"+x+y).append("<div class='slipping-main slipping-main-"+x+y+"'></div>"); // 添加载体元素
                /* 添加图片 */
                $(".slipping-main-"+x+y).append("<div class='slipping-box thumbnail'><img src='/image/avatar/ougen.jpg' style='width:100px;height:60px'></div>");
                $(".slipping-main-"+x+y).append("<div class='slipping-box'></div>"); // 添加空白
                $(".slipping-main-"+x+y).width(mainWidth*2); // 设置载体元素宽度，滑动时滑动的距离是固定的
                right(x,y); // 载体元素左移，显示空白部分，隐藏图片部分
            }
            setTimeout("slippingContainerShow("+x+")",x*500); // 第 x 排横向元素，显示的时间推迟 x * 0.5 秒
        }

        /* 显示横向元素及通过滑动的方式显示其纵向子元素 */
        function slippingContainerShow(x){
            $("#slipping-container-"+x).show();
            for(var y = 1;y <= MAX_Y;++y){
                setTimeout("left("+x+","+y+")",y*200); // 第 y 列纵向元素，显示的时间推迟 y * 0.2 秒
            }
        }
        /* 查看左边的部分，实际上元素向右移 */
        function left(x,y){
            /* 选取横纵坐标匹配的元素，将其按照当前位置向右移动一个子元素宽度 */
            $(".slipping-main-"+x+y).animate({left:$(".slipping-main-"+x+y).position().left+mainWidth,opacity:1},500,function(){});
        }
        /* 查看右侧的部分，实际上元素向左移 */
        function right(x,y){
            $(".slipping-main-"+x+y).animate({left:$(".slipping-main-"+x+y).position().left-mainWidth,opacity:1},500,function(){});
        }
    </script>
@stop