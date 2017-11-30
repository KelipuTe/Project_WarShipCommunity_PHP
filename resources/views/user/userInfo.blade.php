@extends('master.master')
@section('breadCrumb')
    @parent
    <li><a href="">{{$user->username}}</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-offset-1 col-sm-12" role="main">
                <div class="text-center">
                    <img src="{{$user->avatar}}" class="img_avatar_middle img-circle" id="user-avatar">
                </div>
            </div>
            <div class="col-md-7 col-sm-12">
                <div>
                    <div class="form-group user-form-line">
                        <label for="info-uid" class="col-md-2 user-form-lable-line">UID：</label>
                        <div class="col-md-8">
                            <label id="info-uid" class="form-control">{{$user->id}}</label>
                        </div>
                    </div>
                    <div class="form-group user-form-line">
                        <label for="info-username" class="col-md-2 user-form-lable-line">Username：</label>
                        <div class="col-md-8">
                            <label id="info-username" class="form-control">{{$user->username}}</label>
                        </div>
                    </div>
                    <div class="form-group user-form-line">
                        <label for="info-email" class="col-md-2 user-form-lable-line">Email：</label>
                        <div class="col-md-8">
                            <label id="info-email" class="form-control">{{$user->email}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop