@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/forum.css">
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/forum">讨论区</a></li>
@stop