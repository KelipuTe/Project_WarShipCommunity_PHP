@extends('spaceAdministration.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="" id="spaceAdministration-id" name="{{$satellite->id}}">{{$satellite->title}}</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-9">
            {{--卫星主体内容--}}
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h2>{{$satellite->title}}
                        @if(Auth::check() && Auth::user()->id == $satellite->user_id)
                            <a class="btn btn-danger btn-sm pull-right" href="" role="button">爆破</a>
                        @endif
                    </h2>
                </div>
                <div class="panel-body">
                    {!! $html !!}
                </div>
            </div>
        </div>
    </div>
@stop