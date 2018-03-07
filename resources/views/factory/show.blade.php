@extends('factory.app')
@section('content')
    <div class="text-center">
        <button class="btn btn-primary" id="btn-front">前视图</button>
        <button class="btn btn-primary" id="btn-back">后视图</button>
        <button class="btn btn-primary" id="btn-left">左视图</button>
        <button class="btn btn-primary" id="btn-right">右视图</button>
        <button class="btn btn-primary" id="btn-top">俯视图</button>
        <button class="btn btn-primary" id="btn-bottom">底视图</button>
        <button class="hidden" id="btn-prev"></button>
    </div>
    <div class="model-stage">
        <div class="model-container" id="model-container">
            <div class="model-side model-front" id="model-front"></div>
            <div class="model-side model-back" id="model-back"></div>
            <div class="model-side model-left" id="model-left"></div>
            <div class="model-side model-right" id="model-right"></div>
            <div class="model-side model-top" id="model-top"></div>
            <div class="model-side model-bottom" id="model-bottom"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#btn-front').on('click',function () { containerShow('front'); });
            $('#btn-back').on('click',function () { containerShow('back'); });
            $('#btn-left').on('click',function () { containerShow('left'); });
            $('#btn-right').on('click',function () { containerShow('right'); });
            $('#btn-top').on('click',function () { containerShow('top'); });
            $('#btn-bottom').on('click',function () { containerShow('bottom'); });
        });
        
        function containerShow(direction) {
            var prev = $('#btn-prev').text();
            $('#model-container').removeClass(prev);
            $('#model-container').addClass('model-container-show-'+direction);
            $('#btn-prev').text('model-container-show-'+direction)
        }
    </script>
@stop