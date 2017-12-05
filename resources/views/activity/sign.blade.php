@extends('activity.app')
@section('breadCrumb')
    @parent
    <li><a href="">每日签到</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {{--显示每日签到日历的Vue.js组件--}}
            <div id="showSign">
                {{--显示每日签到日历的Vue.js组件--}}
                <show-sign></show-sign>
            </div>
        </div>
    </div>
    {{--引入经过编译的Vuejs组件--}}
    <script src="/js/activitySign.js" type="text/javascript" rel="script"></script>
@stop