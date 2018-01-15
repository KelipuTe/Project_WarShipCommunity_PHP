@extends('office.warship.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <a href="/office/warship/create">新增</a>
            @foreach($warships as $warship)
                <p>
                    <span>{{$warship->id}}</span>
                    <span>{{$warship->classes}}</span>
                    <span>{{$warship->name}}</span>
                    <span>{{$warship->no}}</span>
                    <span>{{$warship->type}}</span>
                    <span>{{$warship->country}}</span>
                    <img src="{{$warship->picture}}">
                </p>
            @endforeach
        </div>
    </div>
@stop