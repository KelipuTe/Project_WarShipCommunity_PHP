@extends('user.center.app')
@section('breadCrumb')
    @parent
    <li><a href="">我的信息</a></li>
@stop
@section('center')
    <div class="panel-heading">我的信息</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group user-form-line">
                    <label for="info-uid" class="col-md-2 col-md-offset-1 user-form-label-line">UID：</label>
                    <div class="col-md-8">
                        <input id="info-uid" type="text" class="form-control" value="{{$user->id}}" readonly/>
                    </div>
                </div>
                <div class="form-group user-form-line">
                    <label for="info-username" class="col-md-2 col-md-offset-1 user-form-label-line">Username：</label>
                    <div class="col-md-8">
                        <input id="info-username" type="text" class="form-control" value="{{$user->username}}" readonly/>
                    </div>
                </div>
                <div class="form-group user-form-line">
                    <label for="info-email" class="col-md-2 col-md-offset-1 user-form-label-line">Email：</label>
                    <div class="col-md-8">
                        <input id="info-email" type="email" class="form-control" value="{{$user->email}}" readonly/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop