@extends('activity.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" role="main">
                <div class="jumbotron">
                    <div class="container">
                        <h2>每日签到
                            <a class="btn btn-primary btn-lg pull-right" href="/activity/sign" role="button">签到</a>
                        </h2>
                    </div>
                </div>
                {{--<div class="jumbotron">
                    <div class="container">
                        <h2>投票
                            <a class="btn btn-primary btn-lg pull-right" href="/activity/vote" role="button">参加</a>
                        </h2>
                    </div>
                </div>--}}
                <div class="jumbotron">
                    <div class="container">
                        <h2>公共聊天室
                            <a class="btn btn-primary btn-lg pull-right" href="/activity/publicChat" role="button">推门</a>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop