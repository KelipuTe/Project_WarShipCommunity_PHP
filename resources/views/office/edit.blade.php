@extends('office.app')
@section('breadCrumb')
    @parent
    <li><a href="">修改报道</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2" role="main">
            @include('error.errorList')
            {!! Form::model($introduction,['method'=>'PATCH','url'=>'/office/show/'.$introduction->id.'/update']) !!}
            <div class="form-group">
                {!! Form::label('title','Title:') !!}
                {!! Form::text('title',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('body','Body:') !!}
                {!! Form::textarea('body',null,['class'=>'form-control']) !!}
            </div>
            <div>
                {!! Form::submit('修改报道',['class'=>'form-control btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop