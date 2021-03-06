@extends('user.center.app')
@section('head')
    @parent
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
    <link type="text/css" rel="stylesheet" href="/ThirdPartyLibrary/Jcrop/css/jquery.Jcrop.css">
    <script type="text/javascript" src="/ThirdPartyLibrary/Jcrop/js/jquery.Jcrop.js"></script>
@stop
@section('breadCrumb')
    @parent
    <li><a href="">我的头像</a></li>
@stop
@section('center')
    <div class="panel-heading">我的头像</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" role="main">
                {{--头像裁剪--}}
                <div class="text-center">
                    <div id="validation-errors"></div>
                    <img src="{{$user->avatar}}" class="img-avatar-large img-circle" id="user-avatar"> {{--显示头像--}}
                    <form action="/user/center/avatar" method="post" id="avatar">
                        <input id="token" name="_token" value="{{csrf_token()}}" type="hidden"> {{--CSRF--}}
                        <div class="text-center">
                            <button type="button" class="btn btn-success avatar-button" id="upload-avatar">更换新的头像</button>
                        </div>
                        {{--这里将<input>type=file标签透明度设为0，并调整z-index使其悬浮于按钮上方--}}
                        <input type="file" name="avatar" id="image" class="avatar" >
                    </form>
                    <div class="span5">
                        <div id="output" style="display:none"></div>
                    </div>
                </div>
            </div>
        </div>
        {{--头像裁剪的遮罩窗体--}}
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="/user/center/avatarCrop" method="post" onsubmit="checkCoords()" id="avatar">
                        <input id="token" name="_token" value="{{csrf_token()}}" type="hidden"> {{--CSRF--}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">裁剪头像</h4>
                        </div>
                        <div class="modal-body">
                            <div class="content">
                                <div class="crop-image-wrapper">
                                    <img src="/image/default_avatar.jpg" class="ui centered image" id="cropbox" >
                                    <input type="hidden" id="photo" name="photo" />
                                    <input type="hidden" id="x" name="x" />
                                    <input type="hidden" id="y" name="y" />
                                    <input type="hidden" id="w" name="w" />
                                    <input type="hidden" id="h" name="h" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">裁剪头像</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--JS部分需要jquery.form.js--}}
    <script>
        $(document).ready(function() {
            var options = {
                beforeSubmit: showRequest,
                success: showResponse,
                dataType: 'json'
            };
            $('#image').on('change', function(){
                $('#upload-avatar').html('正在上传...');
                $('#avatar').ajaxForm(options).submit();
            });
        });
        function showRequest() {
            $("#validation-errors").hide().empty();
            $("#output").css('display','none');
            return true;
        }
        function showResponse(response)  {
            if(response.success == false) {
                var responseErrors = response.errors;
                $.each(responseErrors, function(index, value) {
                    if (value.length != 0) {
                        $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
                    }
                });
                $("#validation-errors").show();
            } else {
                var cropBox = $("#cropbox");
                cropBox.attr('src',response.avatar);
                $('#photo').val(response.image);
                $('#upload-avatar').html('更换新的头像');
                $('#exampleModal').modal('show');
                cropBox.Jcrop({
                    aspectRatio: 1,
                    onSelect: updateCoords,
                    setSelect: [120,120,10,10]
                });
                $('.jcrop-holder img').attr('src',response.avatar);

            }
        }
        /*
         * 在实现图片裁剪时添加的两个 function
         * 获得需要传递到服务器的图片的四个数据
         */
        function updateCoords(c) {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }
        function checkCoords() {
            if (parseInt($('#w').val())) {
                return true;
            }
            alert('请选择图片.');
            return false;
        }
    </script>
@stop