@extends('master.master')
@section('head')
    <link type="text/css" rel="stylesheet" href="/css/permission.css">
@stop
@section('breadCrumb')
    @parent
    <li><a href="/office">办公区</a></li>
    <li><a href="/office/permission">权限管理中心</a></li>
@stop