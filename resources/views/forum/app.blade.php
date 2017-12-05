@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/forum.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/forum">讨论区</a></li>
@stop