@extends('spaceAdministration.app')
@section('content')
    <div class="row">
        <div class="col-md-12" role="main">
            <div class="jumbotron">
                <div class="row">
                    <div class="col-md-10">
                        <h2>侦测到在轨卫星 {{$count}} 个 </h2>
                        <div class="lunbo">
                            {{--卫星条目轮播区域--}}
                            <dl id="lunbo-area" class="lunbo-dl">
                                @foreach($satellites as $satellite)
                                    <dt class="lunbo-dt">
                                        <span>{{$satellite->id}} 号卫星</span>
                                        <a href="/spaceAdministration/show/{{$satellite->id}}"> {{$satellite->title}} </a>
                                        <span>发射于 {{$satellite->created_at}}</span>
                                    </dt>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                    <div class="col-md-2">
                        @if(Auth::check())
                            <a class="btn btn-danger btn-lg text-center" href="/spaceAdministration/create" style="height: 113px;width: 113px" role="button">
                                <span class="fa fa-rocket fa-lg" style="font-size: 80px;margin-top: 20px"></span>
                            </a>
                        @endif
                    </div>
                </div>
                <script>
                    /* 卫星条目轮播效果 */
                    function lunbo( height) {
                        var ul = $('#lunbo-area');
                        var liFirst = ul.find('dt:first'); // 获得 #lunbo-area 下第一个 <dt> 元素
                        /* 动画效果
                         * 将 #lunbo-area 元素上移 50px
                         * 将 #lunbo-area 元素第一个 <li> 子元素克隆并删除
                         * 在 #lunbo-area 元素末尾添加该 <li> 子元素
                         * 将 #lunbo-area 元素高度调整到相对位置 0
                         */
                        $('#lunbo-area').animate({top: height}).animate({"top": 0}, 0, function () {
                            var clone = liFirst.clone(); // 克隆 <dt> 元素
                            $('#lunbo-area').append(clone); // 将克隆的 <dt> 元素添加到 #lunbo-area 末尾
                            liFirst.remove() ;// 删除第一个 <dt> 元素
                        });
                    }
                    setInterval("lunbo('-50px')", 3000); // 每 3 秒执行一次 lunbo() 函数
                </script>
            </div>
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
                    /* 隐藏 dat.gui.js 控件 */
                    $(document).ready(function () {
                        $('.dg').hide();
                    });
                </script>
            </div>
        </div>
    </div>
@stop