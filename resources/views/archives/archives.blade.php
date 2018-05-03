@extends('archives.app')
@section('content')
    <div id="slipping-container-master" style="margin: 0 auto;"></div>

    <script type="text/javascript">
        /*
         * 图片比例
         * 宽度 : 高度 = 150 * 240
         * 宽度 : 高度 = 200 * 320
         */
        var mainWidth = 150; // 图片宽度
        var mainMargin = 2 * 10; // margin 填充宽度
        var x; // 图片横向坐标
        var y; // 图片纵向坐标
        var MAX_X = 2; // 图片横向最大行数
        var MAX_Y = 6; // 图片纵向最大列数
        var totalNum = MAX_X * MAX_Y; // 对象最大数量
        var totalWidth = MAX_Y * (mainWidth + mainMargin); // 载体横向最大宽度

        $(document).ready(function () {
            $.ajax({
                type:'GET',
                url:'/office/warship/getWarship',
                dataType:'json',
                success:function (data) {
                    totalNum = data.length; // 最大对象数就是传递过来的 json 数组元素个数
                    MAX_X = totalNum / MAX_Y + 1;
                    $("#slipping-container-master").css('width',totalWidth);
                    for(x = 1;x <= MAX_X ;++x){
                        // 在修改过显示规格后，需要手动调整横向载体容器高度和显示图片的高度宽度
                        $("#slipping-container-master").append('<div id="slipping-container-'+x+'"></div>'); // 添加横向载体容器元素
                        $("#slipping-container-" + x).css('width',totalWidth);
                        $("#slipping-container-" + x).css('height','250px'); // 在修改过显示规格后，需要手动设置横向载体容器元素高度
                        $("#slipping-container-" + x).hide(); // 隐藏横向元素，在添加纵向元素时保持隐藏
                        for(y = 1;y <= MAX_Y;++y){
                            // 通过坐标计算循环数，检查是否小于最大对象数
                            if(( (x - 1) * MAX_Y + (y - 1) ) < totalNum) {
                                $("#slipping-container-" + x).append("<div class='slipping-show slipping-show-" + x + y + "'></div>"); // 添加观察窗口元素
                                $(".slipping-show-" + x + y).append("<div class='slipping-main slipping-main-" + x + y + "'></div>"); // 添加载体元素
                                // 添加图片，在修改过显示规格后，需要手动调整图片的高度宽度
                                //$(".slipping-main-"+x+y).append("<div class='slipping-box thumbnail'><img src='/uploads/warship/Lexington.png' style='width:150px;height:240px'></div>");
                                $(".slipping-main-"+ x + y).append("<div class='slipping-box thumbnail'>" +
                                    "<a href='/office/warship/" + data[ (x - 1) * MAX_Y + (y - 1) ].name + "'>" +
                                        "<img src='" + data[ (x - 1) * MAX_Y + (y - 1) ].pictureUrl + "' style='width:150px;height:240px'>" +
                                    "</a></div>");
                                $(".slipping-main-" + x + y).append("<div class='slipping-box'></div>"); // 添加空白
                                $(".slipping-main-" + x + y).width(mainWidth * 2); // 设置载体元素宽度，滑动时滑动的距离是固定的
                                right(x, y); // 载体元素左移，显示空白部分，隐藏图片部分
                            }
                        }
                        setTimeout("slippingContainerShow(" + x + ")",x * 500); // 第 x 排横向元素，显示的时间推迟 x * 0.5 秒
                    }
                },
                error:function(jqXHR){
                    console.log("出现错误：" +jqXHR.status);
                }
            });
        });

        /* 显示横向元素及通过滑动的方式显示其纵向子元素 */
        function slippingContainerShow(x){
            $("#slipping-container-" + x).show();
            for (y = 1; y <= MAX_Y; ++y) {
                // 通过坐标计算循环数，检查是否小于最大对象数
                if(( (x - 1) * MAX_Y + (y - 1) ) < totalNum) {
                    setTimeout("left(" + x + "," + y + ")", y * 200); // 第 y 列纵向元素，显示的时间推迟 y * 0.2 秒
                }
            }
        }

        /* 查看左边的部分，实际上元素向右移 */
        function left(x,y){
            // 选取横纵坐标匹配的元素，将其按照当前位置向右移动一个子元素宽度
            $(".slipping-main-" + x + y).animate({left:$(".slipping-main-" + x + y).position().left + mainWidth,opacity:1},500,function(){});
        }

        /* 查看右侧的部分，实际上元素向左移 */
        function right(x,y){
            $(".slipping-main-" + x + y).animate({left:$(".slipping-main-" + x + y).position().left - mainWidth,opacity:1},500,function(){});
        }
    </script>
@stop