@extends('spaceAdministration.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            @if(Auth::check())
                <div class="jumbotron forum-jumbotron">
                    <div class="container">
                        <h2>Welcome！
                            <a class="btn btn-danger btn-lg pull-right" href="/spaceAdministration/create" role="button">发射新的卫星</a>
                        </h2>
                    </div>
                </div>
            @endif
            <div class="jumbotron forum-jumbotron">
                <div class="container">
                    <h2>侦测到在轨卫星 {{$count}} 个</h2>
                </div>
            </div>
            <div class="canvas-container text-center">
                <label id="data-lineCount" class="canvas-data">{{$count}}</label>
                <canvas id="c" class="canvas-size"></canvas>
                {{--<img src="/ThirdPartyLibrary/satellite/a-colours-circle-rainbow-HD-Wallpaper.jpg">--}}
                <script src='/ThirdPartyLibrary/satellite/js/dat.gui.js'></script>
                <script src="/ThirdPartyLibrary/satellite/js/index.js"></script>
                <script>
                    $(document).ready(function () {
                        $('.dg').hide();
                    });
                </script>
            </div>
            <div class="text-center">
                @foreach($spaceAdministrations as $spaceAdministration)
                    <h4 class="media-heading">
                        <span>{{$spaceAdministration->id}} 号卫星</span>
                        <a href="/spaceAdministration/show/{{$spaceAdministration->id}}"> {{$spaceAdministration->title}} </a>
                        <span>发射于 {{$spaceAdministration->created_at}}</span>
                    </h4>
                @endforeach
                <div class="text-center">{!! $spaceAdministrations->render() !!}</div>{{--分页--}}
            </div>
        </div>
    </div>
@stop