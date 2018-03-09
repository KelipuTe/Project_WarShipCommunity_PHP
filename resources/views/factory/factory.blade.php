@extends('factory.app')
@section('content')
    {{--显示列表--}}
    <div class="row">
        <div class="col-md-9">
            <div id="factory-list">
                <factory-list></factory-list>
            </div>
            <div class="text-center">
                <ul id="page-list" class="pagination"></ul>
            </div>
            <template id="template-factory-list">
                <div>
                    <div v-for="factory in factories">
                        <a href="#" :href="['/factory/show/'+factory.id]" class="thumbnail">
                            <img src="" :src="factory.preview" style="height: 200px; width: 400px; display: block;">
                        </a>
                        {{--<div class="thumbnail">
                            <img src="" :src="factory.preview" style="height: 200px; width: 400px; display: block;">
                            <div class="caption">
                                <h3 class="text-center">
                                    <a href="#" :href="['/factory/show/'+factory.id]" class="btn btn-primary"> @{{ factory.title }} </a>
                                </h3>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </template>
            <script>
                Vue.component('factory-list',{
                    template:"#template-factory-list",
                    data:function () {
                        return {
                            factories:''
                        };
                    },
                    created:function () {
                        this.getFactories();
                    },
                    methods:{
                        getFactories:function(){
                            var vm = this;
                            var url = '/factory/getFactories';
                            if( location.href.indexOf('=') != -1 ){
                                //判断是不是翻页后的地址，携带 ?page=number
                                var href = location.href.split('=');
                                url = '/factory/getFactories?page='+ href[href.length-1];
                            }
                            $.ajax({
                                type:'GET',
                                url:url,
                                dataType:'json',
                                success:function (data) {
                                    vm.factories = data.factories.data;
                                    pageList(data.factories,'http://localhost/factory'); // 构造分页按钮列表
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#factory-list"
                });
            </script>
        </div>
        <div class="col-md-3">
            @if(Auth::check())
                <div class="text-center">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" style="font-size: 100px; width: 200px;height: 200px" data-toggle="modal" data-target="#buildModal">
                        <span class="fa fa-cog fa-lg" ></span>
                    </button>
                {{--<button type="button" class="btn btn-primary" @click="getAllTags" data-toggle="modal" data-target="#buildModal">
                    <span class="fa fa-tags fa-lg"></span> 击落卫星
                </button>--}}
                <!-- Modal -->
                    <div class="modal fade" id="buildModal" tabindex="-1" role="dialog" aria-labelledby="buildModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="buildModalLabel"> 重工制造 </h4>
                                </div>
                                <div class="modal-body">
                                    <label for="build-title">模型标题：</label>
                                    <textarea name="build-title" class="form-control" id="build-title" rows="2" style="resize: none"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <button id="build-submit" type="button" class="btn btn-primary">
                                            <span class="fa fa-cog fa-lg"></span> 创建
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#build-submit').on('click',function () {
                $.ajax({
                    type:'GET',
                    url:'/factory/create',
                    data:{ 'title':$('#build-title').val() },
                    dataType:'json',
                    success:function (data) {
                        window.location.href = "/factory/show/"+ data.factory_id;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            });
        });
    </script>
@stop