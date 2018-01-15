@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/warship.css">
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/office">办公区</a></li>
    <li><a href="/office/warship">舰船信息管理中心</a></li>
@stop