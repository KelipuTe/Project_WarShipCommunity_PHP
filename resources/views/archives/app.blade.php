@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/archives.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/archives">冷月档案馆</a></li>
@stop