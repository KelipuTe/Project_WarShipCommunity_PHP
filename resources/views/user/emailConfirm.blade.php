@extends('master.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
                @if($status)
                    <h2>邮箱： {{$email}} 验证成功！</h2>
                @else
                    <h2>验证失败：校验码已过期</h2>
                @endif
            </div>
        </div>
    </div>
@stop