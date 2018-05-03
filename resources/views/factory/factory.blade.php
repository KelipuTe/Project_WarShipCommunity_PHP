@extends('factory.app')
@section('content')
    {{--显示列表--}}
    <div class="row">
        <div class="col-md-8">
            <div id="factory-list">
                <factory-list></factory-list>
            </div>
            <div class="text-center">
                <ul id="page-list" class="pagination"></ul>
            </div>
            <template id="template-factory-list">
                <div>
                    <div v-for="factory in factories" class="text-center">
                        <a href="#" :href="['/factory/show/'+factory.id]" class="thumbnail">
                            <img src="" :src="factory.preview" alt="暂无预览图" style="height: 270px; width: 480px; display: block;">
                            <h3> @{{ factory.title }} </h3>
                        </a>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('factory-list',{
                    template:"#template-factory-list",
                    data:function () {
                        return { factories:'' };
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
                new Vue({ el:"#factory-list" });
            </script>
        </div>
        @if(Auth::check())
            <div id="factory-create">
                <factory-create></factory-create>
            </div>
            <template id="template-factory-create">
                <div class="col-md-4">
                    {{--重工制造--}}
                    <div class="jumbotron text-center">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" style="font-size: 100px; width: 200px;height: 200px" data-toggle="modal" data-target="#buildModal">
                            <span class="fa fa-cog fa-lg" ></span>
                        </button>
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
                                            <button id="build-submit" type="button" class="btn btn-primary" @click="buildFactory">
                                                <span class="fa fa-cog fa-lg"></span> 创建
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--击落卫星--}}
                    <div class="jumbotron text-center">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger" @click="getSatellites" style=" font-size: 100px; width: 200px;height: 200px" data-toggle="modal" data-target="#shotModal">
                            <span class="fa fa-rocket fa-lg" ></span>
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="shotModal" tabindex="-1" role="dialog" aria-labelledby="shotModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="buildModalLabel"> 击落卫星 </h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Username</th>
                                                <th>Created_At</th>
                                                <th>Shot</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="satellite in satellites" class="text-left">
                                                <th v-text="satellite.id"></th>
                                                <td v-text="satellite.title"></td>
                                                <td v-text="satellite.username[0].username"></td>
                                                <td v-text="satellite.created_at"></td>
                                                <td><button class="btn btn-danger btn-sm" @click="shotSatellite(satellite.id)">击落</button></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <script>
                Vue.component('factory-create',{
                    template:"#template-factory-create",
                    data:function () {
                        return { satellites:'' };
                    },
                    created:function () {},
                    methods:{
                        buildFactory:function () {
                            $.ajax({
                                type:'post',
                                url:'/factory/create',
                                data:{ 'title':$('#build-title').val() },
                                dataType:'json',
                                success:function (data) {
                                    if(data.factory_id != 0) {
                                        window.location.href = "/factory/show/" + data.factory_id;
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        getSatellites:function(){
                            var vm = this;
                            $.ajax({
                                type:'GET',
                                url:'/spaceAdministration/getSatellites',
                                data:{},
                                dataType:'json',
                                success:function (data) {
                                    vm.satellites = data.satellites;
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        },
                        shotSatellite:function (satellite_id) {
                            $.ajax({
                                type:'post',
                                url:'/factory/create',
                                data:{ 'satellite_id':satellite_id },
                                dataType:'json',
                                success:function (data) {
                                    if(data.factory_id != 0) {
                                        window.location.href = "/factory/show/" + data.factory_id;
                                    }
                                },
                                error:function(jqXHR){
                                    console.log("出现错误：" +jqXHR.status);
                                }
                            });
                        }
                    }
                });
                new Vue({
                    el:"#factory-create"
                });
            </script>
        @endif
    </div>
@stop