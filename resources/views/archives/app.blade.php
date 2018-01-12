@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/archives.css">
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/archives">冷月档案馆</a></li>
@stop