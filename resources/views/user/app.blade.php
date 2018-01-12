@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/user.css">
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">用户管理</a></li>
@stop