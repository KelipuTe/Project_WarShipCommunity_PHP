@extends('spaceAdministration.app')
@section('breadCrumb')
    @parent
    <li><a href="">发射新的卫星</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            @include('editor::head')
            {!! Form::open(['url'=>'/spaceAdministration/store']) !!}
            <div class="form-group">
                {!! Form::label('title','Title:') !!}
                {!! Form::text('title',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                <div class="editor">
                    {!! Form::textarea('body',null,['id'=>'myEditor','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
            </div>
        </div>
    </div>
@stop