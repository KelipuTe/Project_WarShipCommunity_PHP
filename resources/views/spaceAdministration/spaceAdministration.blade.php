@extends('spaceAdministration.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            {{--判断用户是否登录--}}
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
                    <h2>侦测到在轨卫星 {{$count}} 个 </h2>
                        <div class="lunbo">
                            {{--卫星条目轮播区域--}}
                            <dl id="lunbo-area" class="lunbo-dl">
                                @foreach($spaceAdministrations as $spaceAdministration)
                                    <dt class="lunbo-dt">
                                        <span>{{$spaceAdministration->id}} 号卫星</span>
                                        <a href="/spaceAdministration/show/{{$spaceAdministration->id}}"> {{$spaceAdministration->title}} </a>
                                        <span>发射于 {{$spaceAdministration->created_at}}</span>
                                    </dt>
                                @endforeach
                            </dl>
                        </div>
                </div>
            </div>
                <script>
                    /* 卫星条目轮播效果 */
                    function lunbo( height) {
                        var ul = $('#lunbo-area');
                        var liFirst = ul.find('dt:first');//获得 #lunbo-area 下第一个 <dt> 元素
                        /*动画效果*/
                        /*将 #aaa 元素上移 50px*/
                        /*将 #aaa 元素第一个 <li> 子元素克隆并删除，在末尾添加该元素，将 #aaa 元素高度调整到相对位置 0*/
                        $('#lunbo-area').animate({top: height}).animate({"top": 0}, 0, function () {
                            var clone = liFirst.clone();//克隆 <dt> 元素
                            $('#lunbo-area').append(clone);//将克隆的 <dt> 元素添加到 #lunbo-area 末尾
                            liFirst.remove();//删除第一个 <dt> 元素
                        });
                    }
                    setInterval("lunbo('-50px')", 3000);//每 3 秒执行一次 lunbo() 函数
                </script>
            {{--下方的 3D 圆环效果--}}
            <div class="canvas-container text-center">
                {{--设置后台获得的 3D 圆环效果相关参数--}}
                <label id="data-lineCount" class="canvas-data">{{$count}}</label>
                {{--3D 圆环效果绘图区域--}}
                <canvas id="c" class="canvas-size"></canvas>
                {{--<img src="/ThirdPartyLibrary/satellite/a-colours-circle-rainbow-HD-Wallpaper.jpg">--}}
                {{--引入 3D 圆环效果相关文件--}}
                <script src='/ThirdPartyLibrary/satellite/js/dat.gui.js'></script>
                <script src="/ThirdPartyLibrary/satellite/js/index.js"></script>
                <script>
                    /*隐藏 dat.gui.js 控件*/
                    $(document).ready(function () {
                        $('.dg').hide();
                    });
                </script>
            </div>
        </div>
    </div>
@stop