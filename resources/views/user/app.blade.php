@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/user.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="">用户管理</a></li>
@stop