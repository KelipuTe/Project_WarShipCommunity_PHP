@extends('master.master')
@section('head')
    <link type="text/css" rel="stylesheet" href="/css/warship.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/office">办公区</a></li>
    <li><a href="/office/warship">舰船信息管理中心</a></li>
@stop