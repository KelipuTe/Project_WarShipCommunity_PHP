@extends('master.master')
@section('breadCrumb')
    @parent
    <li><a href="/user/register">注册</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3" role="main">
                @include('error.errorList')
                @if(Session::has('user_register_failed'))
                    <div class="alert alert-danger" role="alert">
                        {{Session::get('user_register_failed')}}
                    </div>
                @endif
                {!! Form::open(['url'=>'/user/create']) !!}
                <div class="form-group">
                    {!! Form::label('username','Username:') !!}
                    {!! Form::text('username',null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email','Email:') !!}
                    {!! Form::email('email',null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password','Password:') !!}
                    {!! Form::password('password',['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirmation','Password_confirmation:') !!}
                    {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
                </div>
                {!! Form::submit('注 册',['class'=>'btn btn-primary form-control']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop