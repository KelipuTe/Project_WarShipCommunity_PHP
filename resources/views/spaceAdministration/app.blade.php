@extends('master.master')
@section('head')
    {{--引入文件--}}
    <link type="text/css" rel="stylesheet" href="/css/spaceAdministration.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/spaceAdministration">冷月航天局</a></li>
@stop