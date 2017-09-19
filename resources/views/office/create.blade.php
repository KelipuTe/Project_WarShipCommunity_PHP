@extends('office.app')
@section('breadCrumb')
    @parent
    <li><a href="">新人报道</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" role="main">
                @include('error.errorList')
                {!! Form::open(['url'=>'/office/store']) !!}
                <div class="form-group">
                    {!! Form::label('title','Title:') !!}
                    {!! Form::text('title','新人报道',['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body','Body:') !!}
                    {!! Form::textarea('body','新人报道，大家好，我是'.Auth::user()->username.'！！',['class'=>'form-control']) !!}
                </div>
                <div>
                    {!! Form::submit('新人报道',['class'=>'form-control btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop