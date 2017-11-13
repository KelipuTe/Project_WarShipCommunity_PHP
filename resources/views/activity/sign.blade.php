@extends('activity.app')
@section('breadCrumb')
    @parent
    <li><a href="">每日签到</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="jumbotron">
                    <div class="container text-center">
                        <h2>每日签到</h2>
                    </div>
                </div>
                {{--签到组件--}}
                <div id="sign">
                    <sign></sign>
                </div>
                {{--签到组件--}}
            </div>
        </div>
    </div>
    {{--用户关注用户的Vue.js组件--}}
    <template id="template-sign">
        <div>
            <div><span v-text="vyear"></span> 年 <span v-text="vmonth"></span> 月</div>
            <div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th id='th_weekday_1'>星期一</th>
                        <th id='th_weekday_2'>星期二</th>
                        <th id='th_weekday_3'>星期三</th>
                        <th id='th_weekday_4'>星期四</th>
                        <th id='th_weekday_5'>星期五</th>
                        <th id='th_weekday_6'>星期六</th>
                        <th id='th_weekday_7'>星期天</th>
                    </tr>
                    </thead>
                    <tbody id="table-day"></tbody>
                </table>
            </div>
            <div>连续签到：<span v-text="vcombo"></span>天</div>
        </div>
    </template>
    <script>
        function sign() {
            var nowTime = new Date();
            var day = jQuery(this).attr("name");
            var btn = jQuery(this);
            jQuery.ajax({
                type:'GET',
                url:'/activity/signIn/'+day,
                dataType:'json',
                success:function (data) {
                    btn.removeClass("btn-primary");
                    btn.addClass("btn-success");
                    btn.text("已签到");
                },
                error:function(jqXHR){
                    console.log("出现错误：" +jqXHR.status);
                }
            });
        }
    </script>
    <script>
        Vue.component('sign',{
            template:'#template-sign',
            data:function () {
                return{
                    year:0,
                    month:0,
                    day:"0",
                    combo:0
                }
            },
            created:function () {
                this.showSign();
            },
            methods:{
                showSign:function () {
                    var vm = this;
                    $.ajax({
                        type:'GET',
                        url:'/activity/showSign',
                        dataType:'json',
                        success:function (data) {
                            vm.year = data.year;
                            vm.month = data.month;
                            vm.day = data.day.split(',');
                            vm.combo = data.combo;
                            var i;
                            for(i = 1;i < 8;++i){
                                jQuery('#th_weekday_'+i).text("星期"+vm.weekday(new Date(data.year,data.month-1,i).getDay()));
                            }
                            /*console.log(new Date(data.year,data.month-1,1).toDateString());
                            console.log("星期"+new Date(data.year,data.month-1,1).getDay());*/
                            vm.showDay(vm.day);
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                showDay:function(day){
                    var i_day = 1;
                    for(;i_day <= day.length;++i_day){
                        /*按一排一周的格式输出日历*/
                        if(i_day % 7 === 1){
                            var i_week = Math.ceil(i_day / 7);
                            var tr_id = "week"+i_week;
                            jQuery('#table-day').append('<tr id=' +tr_id+ '></tr>');
                        }
                        var td_id = "day"+i_day;
                        jQuery('#'+tr_id).append('<td id=' +td_id+ '>' +i_day+ '号</td>');
                        /*添加按钮并绑定事件*/
                        var nowTime = new Date();
                        var td_text = "";
                        var td_btn_class = "";
                        var btn_id = "button"+i_day;
                        if(i_day < nowTime.getDate()){
                            td_text = day[i_day-1] === '1' ? '已签到' : '补签到';
                            td_btn_class = day[i_day-1] === '1' ? 'btn btn-sm btn-success' : 'btn btn-sm btn-primary';
                            jQuery('#'+td_id).append('<button id=' +btn_id+ ' name='+i_day+' class="'+td_btn_class+'">' +td_text+ '</button>');
                            if(!jQuery('#'+btn_id).hasClass('btn-success')){
                                jQuery('#'+btn_id).on('click',sign);
                            }
                        } else if (i_day === nowTime.getDate()){
                            td_text = day[i_day-1] === '1' ? '已签到' : '未签到';
                            td_btn_class = day[i_day-1] === '1' ? 'btn btn-sm btn-success' : 'btn btn-sm btn-primary';
                            jQuery('#'+td_id).append('<button id=' +btn_id+ ' name='+i_day+' class="'+td_btn_class+'">' +td_text+ '</button>');
                            if(!jQuery('#'+btn_id).hasClass('btn-success')){
                                jQuery('#'+btn_id).on('click',sign);
                            }
                        }
                    }
                },
                weekday:function (day) {
                    switch(day){
                        case 0: return "天";
                        case 1: return "一";
                        case 2: return "二";
                        case 3: return "三";
                        case 4: return "四";
                        case 5: return "五";
                        case 6: return "六";
                    }
                }
            },
            computed:{
                vyear:function () {
                    return this.year;
                },
                vmonth:function () {
                    return this.month;
                },
                vday:function () {
                    return this.day;
                },
                vcombo:function () {
                    return this.combo;
                }
            }
        });
        new Vue({
            el:'#sign'
        });
    </script>
    {{--用户关注用户的Vue.js组件--}}
@stop