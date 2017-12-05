@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/activity.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/activity">活动区</a></li>
@stop