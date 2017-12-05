@extends('user.app')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
            @if($status)
                <h2>账号： {{$email}} 激活成功！</h2>
            @else
                <h2>激活失败：校验码已过期</h2>
            @endif
        </div>
    </div>
@stop