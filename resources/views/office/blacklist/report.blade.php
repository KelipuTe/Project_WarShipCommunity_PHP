@extends('office.app')
@section('content')
    <div class="col-md-10 col-md-offset-1" role="main">
        <div id="blacklist-list">
            <blacklist-list></blacklist-list>
        </div>
        <template id="template-blacklist-list">
            <div>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                    <tr class="success">
                        <th class="text-center"> ID </th>
                        <th class="text-center"> 类型 </th>
                        <th class="text-center"> 目标 ID </th>
                        <th class="text-center"> 举报理由 </th>
                        <th class="text-center"> 举报用户 ID </th>
                        <th class="text-center"> 举报时间 </th>
                        <th class="text-center"> 查阅举报对象 </th>
                        <th class="text-center"> 同意举报意见 </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="info" v-for="blacklist in blacklists">
                        <th scope="row" class="text-center"> @{{ blacklist.id }} </th>
                        <td> @{{ blacklist.type }} </td>
                        <td> @{{ blacklist.target }} </td>
                        <td> @{{ blacklist.explain }} </td>
                        <td> @{{ blacklist.user_id }} </td>
                        <td> @{{ blacklist.created_at }} </td>
                        <td><button class="btn btn-primary" role="button" @click="locking(blacklist.id)"> 查阅 </button></td>
                        <td><button class="btn btn-danger" role="button" @click="agree(blacklist.id)"> 同意 </button></td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <ul id="page-list" class="pagination"></ul>
                </div>
            </div>
        </template>
        <script>
            Vue.component('blacklist-list',{
                template:"#template-blacklist-list",
                data:function () {
                    return {
                        blacklists:''
                    };
                },
                created:function () {
                    this.getBlacklists();
                },
                methods:{
                    getBlacklists:function(){
                        var vm = this;
                        var url = '/office/blacklist/getBlacklists';
                        var href = location.href; // 获得地址栏地址
                        if( href.indexOf('?') != -1 ){
                            //判断是不是翻页后的地址，携带 ?page=number
                            href = location.href.split('=');
                            url = '/office/blacklist/getBlacklists?page='+ href[href.length-1];
                        }
                        $.ajax({
                            type:'GET',
                            url:url,
                            dataType:'json',
                            success:function (data) {
                                vm.blacklists = data.blacklists.data;
                                pageList(data.blacklists,'http://localhost/office/blacklist/archives'); // 构造分页按钮列表
                            },
                            error:function(jqXHR){
                                console.log("出现错误：" +jqXHR.status);
                            }
                        });
                    },
                    locking:function(blacklist_id){
                        $.ajax({
                            type:'post',
                            url:'/office/blacklist/locking',
                            data:{
                                'blacklist_id':blacklist_id
                            },
                            dataType:'json',
                            success:function (data) {
                                window.location.href = data.url;
                            },
                            error:function(jqXHR){
                                console.log("出现错误：" +jqXHR.status);
                            }
                        });
                    },
                    agree:function(blacklist_id){
                        $.ajax({
                            type:'post',
                            url:'/office/blacklist/agree',
                            data:{
                                'blacklist_id':blacklist_id
                            },
                            dataType:'json',
                            success:function (data) {
                                alert(data.message);
                                window.location.reload();
                            },
                            error:function(jqXHR){
                                console.log("出现错误：" +jqXHR.status);
                            }
                        });
                    }
                }
            });
            new Vue({
                el:"#blacklist-list"
            });
        </script>
    </div>
@stop