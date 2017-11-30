@extends('master.master')
@section('breadCrumb')
    @parent
    <li><a href="/user/login">登录</a></li>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3" role="main">
                @include('error.errorList')
                @if(Session::has('user_login_failed'))
                    <div class="alert alert-danger" role="alert">
                        {{Session::get('user_login_failed')}}
                    </div>
                @endif
                @if(Session::has('user_register_success'))
                    <div class="alert alert-danger" role="alert">
                        {{Session::get('user_register_success')}}
                    </div>
                @endif
                {!! Form::open(['url'=>'/user/signIn']) !!}
                <div class="form-group">
                    {!! Form::label('email','Email:') !!}
                    {!! Form::email('email',null,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password','Password:') !!}
                    {!! Form::password('password',['class'=>'form-control']) !!}
                </div>
                {!! Form::submit('登 录',['class'=>'btn btn-primary form-control']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop