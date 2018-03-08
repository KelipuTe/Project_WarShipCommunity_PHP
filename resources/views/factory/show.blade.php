@extends('factory.app')
@section('head')
    @parent
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
@stop
@section('content')
    <div class="text-center">
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-front">前视图</button>
            <button class="btn btn-primary" id="edit-front">修改</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-back">后视图</button>
            <button class="btn btn-primary" id="edit-back">修改</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-left">左视图</button>
            <button class="btn btn-primary" id="edit-left">修改</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-right">右视图</button>
            <button class="btn btn-primary" id="edit-right">修改</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-top">俯视图</button>
            <button class="btn btn-primary" id="edit-top">修改</button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="btn-bottom">底视图</button>
            <button class="btn btn-primary" id="edit-bottom">修改</button>
        </div>
        <button class="hidden" id="btn-prev"></button>

        <div class="modal fade" id="viewPictureModal" tabindex="-1" role="dialog" aria-labelledby="viewPictureModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="viewPictureModalLabel"> 重工制造 </h4>
                    </div>
                    <div class="modal-body">
                        <form id="view-picture" method="post" action="/factory/editView">
                            <input type="text" id="factory-id" name="factory-id" class="hidden"/>
                            <input type="text" id="view-picture-direction" name="view-direction" class="hidden"/>
                            <input type="file" id="view-picture-file" name="view-file" class="picture"/>
                            <div class="input-group">
                                <label for="view-picture-name" class="hidden"></label>
                                <input type="text" id="view-picture-name" name="view-name" class="form-control" />
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success">上传视图</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button id="build-submit" type="button" class="btn btn-primary">
                                <span class="fa fa-paper-plane-o fa-lg"></span> 创建
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
            var href = location.href.split('/');
            var factory_id = href[href.length-1];
            $.ajax({
                type:'GET',
                url:'/factory/getFactory',
                data:{ 'factory_id':factory_id },
                dataType:'json',
                success:function (data) {
                    changeView('front',data.factory.view_front);
                    changeView('back',data.factory.view_back);
                    changeView('left',data.factory.view_left);
                    changeView('right',data.factory.view_right);
                    changeView('top',data.factory.view_top);
                    changeView('bottom',data.factory.view_bottom);
                },
                error:function(jqXHR){
                    console.log("出现错误：" +jqXHR.status);
                }
            });

            var options = {
                success: showResponse,
                dataType: 'json'
            };

            $('#build-submit').on('click',function () {
                $('#view-picture').ajaxForm(options).submit();
            });

            $('#view-picture-file').on('change',function () {
                var view_picture = $('#view-picture-file').val();
                view_picture = view_picture.split('\\'); // 转义
                var view_picture_name = view_picture[view_picture.length-1];
                $('#view-picture-name').val(view_picture_name);
            });

            // 旋转按钮
            $('#btn-front').on('click',function () { containerShow('front'); });
            $('#btn-back').on('click',function () { containerShow('back'); });
            $('#btn-left').on('click',function () { containerShow('left'); });
            $('#btn-right').on('click',function () { containerShow('right'); });
            $('#btn-top').on('click',function () { containerShow('top'); });
            $('#btn-bottom').on('click',function () { containerShow('bottom'); });

            // 修改按钮
            $('#edit-front').on('click',function () { editView('front'); });
            $('#edit-back').on('click',function () { editView('back'); });
            $('#edit-left').on('click',function () { editView('left'); });
            $('#edit-right').on('click',function () { editView('right'); });
            $('#edit-top').on('click',function () { editView('top'); });
            $('#edit-bottom').on('click',function () { editView('bottom'); });
        });
        
        function containerShow(direction) {
            var prev = $('#btn-prev').text();
            $('#model-container').removeClass(prev);
            $('#model-container').addClass('model-container-show-'+direction);
            $('#btn-prev').text('model-container-show-'+direction)
        }

        function changeView(direction,url){
            if(url != null && url != "") {
                console.log(direction+'-'+url);
                $('#model-'+direction).css('background','url(' +url+ ')');
            }
        }

        function editView(direction){
            var href = location.href.split('/');
            var factory_id = href[href.length-1];
            $('#factory-id').val(factory_id);
            $('#view-picture-direction').val(direction);
            $('#viewPictureModal').modal('toggle');
        }

        function showResponse(response)  {
            if(response.success == false) {
                console.log(response.errors);
            } else {
                changeView(response.direction,response.view);
            }
            $('#viewPictureModal').modal('toggle');
        }
    </script>
@stop