@extends('factory.app')
@section('head')
    @parent
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
@stop
@section('content')
    <div id="factory">
        <factory></factory>
    </div>
    <template id="template-factory">
        <div class="row">
            <div class="col-md-9">
                <div class="model-stage">
                    <div class="model-container" id="model-container">
                        {{--<div class="model-side model-front" id="model-front"></div>
                        <div class="model-side model-back" id="model-back"></div>
                        <div class="model-side model-left" id="model-left"></div>
                        <div class="model-side model-right" id="model-right"></div>
                        <div class="model-side model-top" id="model-top"></div>
                        <div class="model-side model-bottom" id="model-bottom"></div>--}}
                        <div class="model-side model-front"><img src="" id="model-front" class="view-picture-size"></div>
                        <div class="model-side model-back"><img src="" id="model-back" class="view-picture-size"></div>
                        <div class="model-side model-left"><img src="" id="model-left" class="view-picture-size"></div>
                        <div class="model-side model-right"><img src="" id="model-right" class="view-picture-size"></div>
                        <div class="model-side model-top"><img src="" id="model-top" class="view-picture-size"></div>
                        <div class="model-side model-bottom"><img src="" id="model-bottom" class="view-picture-size"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center function-panel">
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-preview" @click="showPreview">预览图</button>
                    <button class="btn btn-primary" id="edit-preview" v-if="isUser" @click="editView('preview')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-front" @click="containerShow('front')">前视图</button>
                    <button class="btn btn-primary" id="edit-front" v-if="isUser" @click="editView('front')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-back" @click="containerShow('back')">后视图</button>
                    <button class="btn btn-primary" id="edit-back" v-if="isUser" @click="editView('back')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-left" @click="containerShow('left')">左视图</button>
                    <button class="btn btn-primary" id="edit-left" v-if="isUser" @click="editView('left')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-right" @click="containerShow('right')">右视图</button>
                    <button class="btn btn-primary" id="edit-right" v-if="isUser" @click="editView('right')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-top" @click="containerShow('top')">俯视图</button>
                    <button class="btn btn-primary" id="edit-top" v-if="isUser" @click="editView('top')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <button class="btn btn-primary" id="btn-bottom" @click="containerShow('bottom')">底视图</button>
                    <button class="btn btn-primary" id="edit-bottom" v-if="isUser" @click="editView('bottom')">修改</button>
                </div><br>
                <div class="btn-group function-panel-btn">
                    <a class="btn btn-primary" :href="fileURL">原文件</a>
                    <button class="btn btn-primary" v-if="isUser" @click="filePost">上传</button>
                </div><br>
                <button class="hidden" id="btn-prev"></button>
                {{--预览视图模态框--}}
                <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="previewModalLabel"> 预览图 </h4>
                            </div>
                            <div class="modal-body">
                                <img v-if="preview" src="" :src="preview">
                                <p v-else="preview"> 暂无预览图 </p>
                            </div>
                        </div>
                    </div>
                </div>
                {{--修改视图模态框--}}
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
                                            <button type="button" class="btn btn-success"> 选择视图 </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <button id="build-submit" type="button" class="btn btn-primary">
                                        <span class="fa fa-paper-plane-o fa-lg"></span> 上传
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--原文件上传模态框--}}
                <div class="modal fade" id="filePostModal" tabindex="-1" role="dialog" aria-labelledby="filePostModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="filePostModalLabel"> 原文件上传 </h4>
                            </div>
                            <div class="modal-body">
                                <form id="file-post" method="post" action="/factory/filePost">
                                    <input type="text" id="file-post-factory-id" name="file-post-factory-id" class="hidden"/>
                                    <input type="file" id="file-post-file" name="file-post-file" class="picture"/>
                                    <div class="input-group">
                                        <label for="file-post-name" class="hidden"></label>
                                        <input type="text" id="file-post-name" name="file-post-name" class="form-control" />
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-success"> 选择文件 </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <button id="file-post-submit" type="button" class="btn btn-primary">
                                        <span class="fa fa-paper-plane-o fa-lg"></span> 上传
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        Vue.component('factory',{
            template:"#template-factory",
            data:function () {
                return {
                    factory:'',
                    preview:null,
                    fileURL:null,
                    isUser:''
                };
            },
            created:function () {
                this.getFactory();
            },
            methods:{
                getFactory:function(){
                    var vm = this;
                    var href = location.href.split('/');
                    var factory_id = href[href.length-1];
                    $.ajax({
                        type:'GET',
                        url:'/factory/getFactory',
                        data:{ 'factory_id':factory_id },
                        dataType:'json',
                        success:function (data) {
                            vm.factory = data.factory;
                            vm.preview = data.factory.preview;
                            vm.fileURL = data.factory.file;
                            vm.isUser = data.isUser;
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
                },
                containerShow:function(direction) {
                    var prev = $('#btn-prev').text();
                    $('#model-container').removeClass(prev);
                    $('#model-container').addClass('model-container-show-'+direction);
                    $('#btn-prev').text('model-container-show-'+direction);
                },
                editView:function(direction){
                    var href = location.href.split('/');
                    var factory_id = href[href.length-1];
                    $('#factory-id').val(factory_id);
                    $('#view-picture-direction').val(direction);
                    $('#viewPictureModal').modal('toggle');
                },
                showPreview:function () {
                    $('#previewModal').modal('toggle');
                },
                filePost:function(){
                    var href = location.href.split('/');
                    var factory_id = href[href.length-1];
                    $('#file-post-factory-id').val(factory_id);
                    $('#filePostModal').modal('toggle');
                }
            }
        });
        new Vue({
            el:"#factory"
        });
    </script>
    <script>
        $(document).ready(function () {
            // 视图上传
            var viewPicture = {
                success: viewPictureShowResponse,
                dataType: 'json'
            };
            $('#view-picture-file').on('change',function () {
                var view_picture = $('#view-picture-file').val();
                view_picture = view_picture.split('\\'); // 转义
                var view_picture_name = view_picture[view_picture.length-1];
                $('#view-picture-name').val(view_picture_name);
            });
            $('#build-submit').on('click',function () {
                $('#view-picture').ajaxForm(viewPicture).submit();
            });

            // 文件上传
            var filePost = {
                success: filePostShowResponse,
                dataType: 'json'
            };
            $('#file-post-file').on('change',function () {
                var file_post = $('#file-post-file').val();
                file_post = file_post.split('\\'); // 转义
                var file_post_name = file_post[file_post.length-1];
                $('#file-post-name').val(file_post_name);
            });
            $('#file-post-submit').on('click',function () {
                $('#file-post').ajaxForm(filePost).submit();
            });
        });

        // 改变立方体各面背景图片
        function changeView(direction,url){
            if(url != null && url != "") {
                //$('#model-'+direction).css('background','url(' +url+ ')');
                $('#model-'+direction).attr('src',url);
            }
        }

        // 视图上传结果处理
        function viewPictureShowResponse(response)  {
            $('#viewPictureModal').modal('toggle');
            if(response.success == false) {
                console.log(response.errors);
            } else {
                if(response.direction == 'preview'){
                    window.location.reload();
                } else{
                    changeView(response.direction,response.view);
                }
            }
        }

        // 文件上传结果处理
        function filePostShowResponse(response)  {
            $('#filePostModal').modal('toggle');
            if(response.success == false) {
                console.log(response.errors);
            } else {
                window.location.reload();
            }
        }
    </script>
@stop