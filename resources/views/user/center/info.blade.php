@extends('user.center.app')
@section('breadCrumb')
    @parent
    <li><a href="">{{$user->username}}</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="text-center">
                <img src="{{$user->avatar}}" class="img-avatar-large img-circle">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-group user-form-line">
                <label class="col-md-3 user-form-label-line">UID：</label>
                <div class="col-md-9">
                    <label class="form-control">{{$user->id}}</label>
                </div>
            </div>
            <div class="form-group user-form-line">
                <label class="col-md-3 user-form-label-line">Username：</label>
                <div class="col-md-9">
                    <label class="form-control">{{$user->username}}</label>
                </div>
            </div>
            <div class="form-group user-form-line">
                <label class="col-md-3 user-form-label-line">Email：</label>
                <div class="col-md-9">
                    <label class="form-control">{{$user->email}}</label>
                </div>
            </div>
        </div>
    </div>
@stop