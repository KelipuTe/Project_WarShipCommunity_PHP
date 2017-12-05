@extends('office.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#introduction" aria-controls="introduction" role="tab" data-toggle="tab">新人报道</a>
                </li>
                <li role="presentation">
                    <a href="#violations" aria-controls="violations" role="tab" data-toggle="tab">小黑屋</a>
                </li>
                <li role="presentation">
                    <a href="#office" aria-controls="office" role="tab" data-toggle="tab">办公室</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="introduction">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1" role="main">
                            @foreach($introductions as $introduction)
                                <div class="media office-line">
                                    <div class="media-left">
                                        <img class="media-object img-circle img_avatar_small" src="{{$introduction->user->avatar}}">
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="/office/show/{{$introduction->id}}">{{$introduction->title}}</a>
                                        </h4>
                                        {{$introduction->user->username}}
                                        <div class="pull-right">
                                            <span>{{count($introduction->messages)}}回复</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="/office/create" class="form-control btn btn-success">我要报道</a>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="violations"></div>
                <div role="tabpanel" class="tab-pane" id="office"></div>
            </div>
        </div>
    </div>
@stop