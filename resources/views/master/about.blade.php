@extends('master.master')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/about">关于</a></li>
@stop
@section('content')
    <div class="row">
        <ul>
            <li><a href="https://github.com/KelipuTe/Project_WarShipCommunity_PHP">GitHub</a></li>
        </ul>
    </div>
@stop