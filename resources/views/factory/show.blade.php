<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::check() ? 'Bearer '.Auth::user()->api_token : 'Bearer ' }}">
    <title>WarShipCommunity</title>
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/factoryShow.css">
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <script type="text/javascript" rel="script" src="https://cdn.bootcss.com/vue/2.4.4/vue.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
</head>
<body class="model-body">
<div class="master-alert">
    <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
</div>
<div id="factory">
    <factory></factory>
</div>
<template id="template-factory">
    <div class="model-stage">
        <div class="model-main main-left">
            <div class="left-top"></div>
            <div class="left-top-over">
                <button type="button" class="btn-left-top">
                    <span class="fa fa-gear"></span>
                </button>
                <button type="button" class="btn-left-top">
                    <span class="fa fa-gear"></span>
                </button>
            </div>
            <div class="left-top-right"></div>
            <div class="left-top-right-over">
                <p v-text="user.username"></p>
            </div>
            <div class="left-center-top"></div>
            <div class="left-center-top-over text-center">
                <h3 class="left-center-top-text">模型简介</h3>
            </div>
            <div class="left-center"></div>
            <div class="left-center-over">
                <p v-text="factory.introduction"></p>
            </div>
            <div class="left-center-bottom">
                <p v-text="factory.hot_Factory"></p>
                <p v-text="factory.nice_Factory"></p>
            </div>
            <div class="left-bottom"></div>
            <div class="left-bottom-over">
                <button type="button" class="btn-left-bottom" v-if="notEdit" @click="doNotEdit">
                    <span class="fa fa-gear"></span>
                </button>
                <button type="button" class="btn-left-bottom-edit" v-else="notEdit" @click="doNotEdit">
                    <span class="fa fa-gear"></span>
                </button>
                <button type="button" class="btn-left-bottom" @click="turnBack">
                    <span class="fa fa-sign-out"></span>
                </button>
            </div>
            <div class="left-bottom-right"></div>
            <div class="left-bottom-right-over">
                <p>推荐</p>
            </div>
        </div>
        <div class="model-main main-center">
            <div class="center-top text-center">
                <div class="center-top-box">
                    <div class="center-left-data-box">
                        <h4 v-text="factory.point"></h4>
                    </div>
                    <div class="center-left-text-box"><h4>顶点数</h4></div>
                    <div class="center-left-text-circle">1</div>
                    <div class="center-right-data-box">
                        <h4 v-text="factory.plane"></h4>
                    </div>
                    <div class="center-right-text-box"><h4>面数</h4></div>
                    <div class="center-right-text-circle">2</div>
                </div>
                <div class="center-top-box">
                    <div class="center-left-data-box">
                        <h4 v-text="factory.type"></h4>
                    </div>
                    <div class="center-left-text-box"><h4>类型</h4></div>
                    <div class="center-left-text-circle">3</div>
                    <div class="center-right-data-box">
                        <h4 v-text="factory.size"></h4>
                    </div>
                    <div class="center-right-text-box"><h4>大小</h4></div>
                    <div class="center-right-text-circle">4</div>
                </div>
            </div>
            <div class="center-center"></div>
            <div class="center-center-stage">
                <div class="model-container" id="model-container">
                    <img class="model-side model-front" src="" :src="factory.view_front">
                    <img class="model-side model-back" src="" :src="factory.view_back">
                    <img class="model-side model-left" src="" :src="factory.view_left">
                    <img class="model-side model-right" src="" :src="factory.view_right">
                    <img class="model-side model-top" src="" :src="factory.view_top">
                    <img class="model-side model-bottom" src="" :src="factory.view_bottom">
                </div>
            </div>
            <div class="center-center-over text-center">
                <div class="radar"></div>
                <div class="radar-base">目标寻获</div>
            </div>
            <div class="center-bottom text-center">
                <h3 v-text="factory.title"></h3>
            </div>
        </div>
        <div class="model-main main-right">
            <div class="right-top text-center">
                <div class="btn-group" v-if="notEdit">
                    <a class="btn btn-primary btn-lg" id="fileURL" :href="[factory.file]">
                        <span class="fa fa-download"></span> 模型文件
                    </a>
                </div>
                <div class="btn-group" v-else="notEdit">
                    <button class="btn btn-warning btn-lg" @click="fileEdit">模型文件</button>
                    <button class="btn btn-warning btn-lg" @click="infoEdit">模型信息</button>
                </div>
            </div>
            <div class="right-center" v-if="notEdit">
                <div class="right-center-north text-center">
                    <button class="btn btn-primary btn-right-center-north" @click="containerShow('front')">前视图</button>
                    <button class="btn btn-primary btn-right-center-north" @click="containerShow('left')">左视图</button>
                    <button class="btn btn-primary btn-right-center-north" @click="containerShow('top')">顶视图</button>
                </div>
                <div class="right-center-center text-center">
                    <button class="btn btn-primary btn-right-center-center" @click="showPreview">预览图</button>
                    <button class="hidden" id="btn-prev"></button>
                </div>
                <div class="right-center-south text-center">
                    <button class="btn btn-primary btn-right-center-south" @click="containerShow('back')">后视图</button>
                    <button class="btn btn-primary btn-right-center-south" @click="containerShow('right')">右视图</button>
                    <button class="btn btn-primary btn-right-center-south" @click="containerShow('bottom')">底视图</button>
                </div>
            </div>
            <div class="right-center" v-else="notEdit">
                <div class="right-center-north text-center">
                    <button class="btn btn-warning btn-right-center-north" @click="viewEdit('front')">前视图</button>
                    <button class="btn btn-warning btn-right-center-north" @click="viewEdit('left')">左视图</button>
                    <button class="btn btn-warning btn-right-center-north" @click="viewEdit('top')">顶视图</button>
                </div>
                <div class="right-center-center text-center">
                    <button class="btn btn-warning btn-right-center-center" @click="viewEdit('preview')">预览图</button>
                    <button class="hidden" id="btn-prev"></button>
                </div>
                <div class="right-center-south text-center">
                    <button class="btn btn-warning btn-right-center-south" @click="viewEdit('back')">后视图</button>
                    <button class="btn btn-warning btn-right-center-south" @click="viewEdit('right')">右视图</button>
                    <button class="btn btn-warning btn-right-center-south" @click="viewEdit('bottom')">底视图</button>
                </div>
            </div>
            <div class="right-bottom">
                <div class="right-bottom-circle"></div>
            </div>
        </div>
        {{--视图预览模态框--}}
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="previewModalLabel"> 预览图 </h4>
                    </div>
                    <div class="modal-body text-center" style="overflow: scroll">
                        <img v-if="factory.preview" src="" :src="factory.preview">
                        <p v-else="factory.preview"> 暂无预览图 </p>
                    </div>
                </div>
            </div>
        </div>
        {{--修改模态框--}}
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="editModalLabel" v-if="editType == 1"> 模型信息 </h4>
                        <h4 class="modal-title" id="editModalLabel" v-else-if="editType == 2"> 模型视图 </h4>
                        <h4 class="modal-title" id="editModalLabel" v-else-if="editType == 3"> 模型文件 </h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit" method="post" action="/factory/infoEdit" v-if="editType == 1">
                            <input type="text" name="factory-id" v-model="factory.id" class="hidden"/>
                            <div class="form-group">
                                <label>模型标题：</label>
                                <p class="form-control" v-text="factory.title"></p>
                            </div>
                            <div class="form-group">
                                <label for="factory-introduction">模型简介：</label>
                                <textarea class="form-control" id="factory-introduction" name="factory-introduction"
                                          rows="3" style="resize: none" v-model="factory.introduction"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="factory-point">顶点数：
                                    <input class="form-control" type="text"
                                           id="factory-point" name="factory-point" v-model="factory.point"/>
                                </label>
                                <label for="factory-plane">面数：
                                    <input class="form-control" type="text"
                                           id="factory-plane" name="factory-plane" v-model="factory.plane"/>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="factory-type">类型：
                                    <input class="form-control" type="text"
                                           id="factory-type" name="factory-type" v-model="factory.type"/>
                                </label>
                                <label for="factory-size">大小：
                                    <input class="form-control" type="text"
                                           id="factory-size" name="factory-size" v-model="factory.size"/>
                                </label>
                            </div>
                        </form>
                        <form id="form-edit" method="post" action="/factory/viewEdit" v-else-if="editType == 2">
                            <input type="text" name="factory-id" v-model="factory.id" class="hidden"/>
                            <input type="text" name="view-direction" v-model="direction" class="hidden"/>
                            <input type="file" id="view-file" name="view-file" class="picture" @change="viewFileChange"/>
                            <div class="input-group">
                                <label for="view-name" class="hidden"></label>
                                <input type="text" id="view-name" name="view-name" class="form-control" />
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success"> 选择视图 </button>
                                </div>
                            </div>
                        </form>
                        <form id="form-edit" method="post" action="/factory/fileEdit" v-else-if="editType == 3">
                            <input type="text" name="factory-id" v-model="factory.id" class="hidden"/>
                            <input type="file" id="model-file" name="model-file" class="picture" @change="modelFileChange"/>
                            <div class="input-group">
                                <label for="model-name" class="hidden"></label>
                                <input type="text" id="model-name" name="model-name" class="form-control" />
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success"> 选择文件 </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <button id="build-submit" type="button" class="btn btn-primary"
                                    @click="infoUpload" v-if="editType == 1">
                                <span class="fa fa-paper-plane-o fa-lg"></span> 修改信息
                            </button>
                            <button id="build-submit" type="button" class="btn btn-primary"
                                    @click="viewUpload" v-else-if="editType == 2">
                                <span class="fa fa-paper-plane-o fa-lg"></span> 上传视图
                            </button>
                            <button id="file-edit-submit" type="button" class="btn btn-primary"
                                    @click="fileUpload" v-else-if="editType == 3">
                                <span class="fa fa-paper-plane-o fa-lg"></span> 上传文件
                            </button>
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
                factory: '',
                user: '',
                direction: '',
                owner: false,
                notEdit: true,
                editType: 1
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
                        vm.owner = data.owner;
                        vm.user = vm.factory.userInfo;
                        factoryShow();
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            },
            doNotEdit:function () {
                this.notEdit = ! this.notEdit;
            },
            turnBack:function () {
                history.back();
            },
            containerShow:function(direction) {
                var prev = $('#btn-prev').text();
                $('#model-container').removeClass(prev);
                $('#model-container').addClass('model-container-show-'+direction);
                $('#btn-prev').text('model-container-show-'+direction);
            },
            showPreview:function () {
                $('#previewModal').modal('toggle');
            },
            setFactoryId:function () {
                var href = location.href.split('/');
                this.factory_id = href[href.length-1];
            },
            infoEdit:function () {
                this.editType = 1;
                $('#editModal').modal('toggle');
                this.setFactoryId();
            },
            viewEdit:function(direction){
                this.editType = 2;
                $('#editModal').modal('toggle');
                this.setFactoryId();
                this.direction = direction;
            },
            fileEdit:function(){
                this.editType = 3;
                this.setFactoryId();
                $('#editModal').modal('toggle');
            },
            viewFileChange:function(){
                var view_picture = $('#view-file').val();
                view_picture = view_picture.split('\\'); // 转义
                var view_picture_name = view_picture[view_picture.length-1];
                $('#view-name').val(view_picture_name);
            },
            modelFileChange:function () {
                var file_model = $('#model-file').val();
                file_model = file_model.split('\\'); // 转义
                var file_model_name = file_model[file_model.length-1];
                $('#model-name').val(file_model_name);
            },
            infoUpload:function () {
                var vm = this;
                var infoPost = {
                    success: vm.infoPostShowResponse,
                    dataType: 'json'
                };
                $('#form-edit').ajaxForm(infoPost).submit();
            },
            viewUpload:function () {
                var vm = this;
                var viewPost = {
                    success: vm.viewPostShowResponse,
                    dataType: 'json'
                };
                $('#form-edit').ajaxForm(viewPost).submit();
            },
            fileUpload:function () {
                var vm = this;
                var filePost = {
                    success: vm.filePostShowResponse,
                    dataType: 'json'
                };
                $('#form-edit').ajaxForm(filePost).submit();
            },
            infoPostShowResponse:function(response){
                $('#editModal').modal('toggle');
                if(response.success == false) {
                    $.each(response.errors,function (index,value) {
                        makeAlertBox('danger',value);
                    });
                } else {
                    makeAlertBox('success','修改成功');
                }
            },
            viewPostShowResponse:function(response)  {
                var vm = this;
                $('#editModal').modal('toggle');
                if(response.success == false) {
                    console.log(response.errors);
                } else {
                    switch(response.direction){
                        case 'preview':vm.factory.preview = response.viewURL;break;
                        case 'front':vm.factory.view_front = response.viewURL;break;
                        case 'back':vm.factory.view_back = response.viewURL;break;
                        case 'left':vm.factory.view_left = response.viewURL;break;
                        case 'right':vm.factory.view_right = response.viewURL;break;
                        case 'top':vm.factory.view_top = response.viewURL;break;
                        case 'bottom':vm.factory.view_bottom = response.viewURL;break;
                        default:break;
                    }
                }
            },
            filePostShowResponse:function(response)  {
                var vm = this;
                $('#editModal').modal('toggle');
                if(response.success == false) {
                    $.each(response.errors,function (index,value) {
                        makeAlertBox('danger',value);
                    });
                } else {
                    vm.factory.file = response.fileURL;
                }
            }
        }
    });
    new Vue({ el:"#factory" });
</script>
<script>
    $(document).ready(function () {
        /* 添加 CSRF 保护 */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': $('meta[name="api-token"]').attr('content')
            }
        });
    });
    // 页面展示延时动画
    function factoryShow(){
        $('.radar-base').css('animation','radar-text 1s infinite linear'); // 雷达文字

        setTimeout(function(){
            $('.center-top').css('opacity',1); // 中间头部
            $('.center-bottom').css('opacity',1); // 中间底部
        },1000);

        setTimeout(function () {
            $('.left-center-top').css('opacity',1); // 左侧中间头部
            $('.left-center-bottom').css('opacity',1); // 左侧中间底部
            $('.left-center-top-over').css('opacity',1); // 左侧中间头部

            $('.center-left-text-circle').css('opacity',1); // 中间顶部文字圆形
            $('.center-right-text-circle').css('opacity',1); // 中间顶部文字圆形
        },2000);
        setTimeout(function () {
            $('.left-center-top').css('top','200px'); // 左侧中间头部
            $('.left-center-bottom').css('top','550px'); // 左侧中间底部
            $('.left-center-top-over').css('top','200px'); // 左侧中间头部

            $('.center-left-text-circle').css('left','180px'); // 中间顶部文字圆形
            $('.center-right-text-circle').css('left','250px'); // 中间顶部文字圆形
        },2500);

        setTimeout(function () {
            $('.left-center').css('left','25px'); // 左侧中间矩形
            $('.left-center-over').css('left','25px'); // 左侧中间矩形

            $('.center-left-text-box').css('opacity',1); // 中间顶部文字矩形
            $('.center-right-text-box').css('opacity',1); // 中间顶部文字矩形
        },3000);
        setTimeout(function () {
            $('.center-left-text-box').css('left','90px'); // 中间顶部文字矩形
            $('.center-right-text-box').css('left','310px'); // 中间顶部文字矩形
        },3500);

        setTimeout(function () {
            $('.left-top-right').css('top','25px'); // 左侧顶部右侧矩形
            $('.left-bottom-right').css('bottom','25px'); // 左侧底部右侧矩形
            $('.left-top-right-over').css('top','25px'); // 左侧顶部右侧矩形
            $('.left-bottom-right-over').css('bottom','25px'); // 左侧底部右侧矩形

            $('.center-left-data-box').css('opacity',1); // 中间顶部数据矩形
            $('.center-right-data-box').css('opacity',1); // 中间顶部数据矩形
        },4000);
        setTimeout(function () {
            $('.center-left-data-box').css('left','0'); // 中间顶部数据矩形
            $('.center-right-data-box').css('left','400px'); // 中间顶部数据矩形
        },4500);

        setTimeout(function () {
            $('.left-top').css('left',0); // 左侧顶部左侧
            $('.left-bottom').css('left',0); // 左侧底部左侧
            $('.left-top-over').css('left',0); // 左侧顶部左侧
            $('.left-bottom-over').css('left',0); // 左侧底部左侧

            $('.right-top').css('opacity',1); // 右侧底部下方按钮

            $('.right-center-north').css('top',0); // 右侧底部上方按钮
            $('.right-center-center').css('left',0); // 右侧底部中部按钮
            $('.right-center-south').css('bottom',0); // 右侧底部下方按钮
        },5000);

        setTimeout(function () {
            $('.center-center-over').css('opacity',0); // 雷达文字
        },6000);
    }
    /*
     * 生成警告框
     * type 警告框类型
     * value 警告框提示信息
     *
     * 在页面中需要如下结构
     * <div class="master-alert">
     *     <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
     * </div>
     */
    function makeAlertBox(type,value){
        var title;
        switch (type) {
            case 'success' :
                title = '成功！';
                break;
            case 'info' :
                title = '信息！';
                break;
            case 'warning' :
                title = '警告！';
                break;
            case 'danger' :
                title = '错误！';
                break;
            default :
                title = '错误！';
        }
        $('#master-alert-container').append(
            '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<strong>' + title + '</strong>' + value +
            '</div>');
    }
</script>
</body>
</html>