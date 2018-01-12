@extends('activity.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">每日签到</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {{--显示每日签到日历的Vue.js组件--}}
            <div id="showSign">
                <show-sign></show-sign>{{--显示每日签到日历的Vue.js组件--}}
            </div>
        </div>
    </div>
    {{--引入经过编译的Vuejs组件--}}
    <script src="/js/activitySign.js" type="text/javascript" rel="script"></script>
@stop