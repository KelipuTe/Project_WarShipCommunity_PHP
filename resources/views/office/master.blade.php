@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/office.css">
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="/office">办公区</a></li>
@stop