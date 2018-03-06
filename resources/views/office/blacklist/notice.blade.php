@extends('office.master')
@section('content')
    <div class="col-md-10 col-md-offset-1" role="main">
        <div id="blacklist-list">
            <blacklist-list></blacklist-list>
        </div>
        <div class="text-center">
            <ul id="page-list" class="pagination"></ul>
        </div>
        <template id="template-blacklist-list">
            <div>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                    <tr class="success" >
                        <th class="text-center"> ID </th>
                        <th class="text-center"> 类型 </th>
                        <th class="text-center"> 目标 ID </th>
                        <th class="text-center"> 举报理由 </th>
                        <th class="text-center"> 处理决定 </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="info" v-for="blacklist in blacklists">
                        <th scope="row" class="text-center"> @{{ blacklist.id }} </th>
                        <td> @{{ blacklist.type }} </td>
                        <td> @{{ blacklist.target }} </td>
                        <td> 举报理由 </td>
                        <td> 处理决定 </td>
                    </tr>
                    </tbody>
                </table>
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
                    this.getDoneBlacklists();
                },
                methods:{
                    getDoneBlacklists:function(){
                        var vm = this;
                        var url = '/office/blacklist/getDoneBlacklists';
                        var href = location.href; // 获得地址栏地址
                        if( href.indexOf('?') != -1 ){
                            //判断是不是翻页后的地址，携带 ?page=number
                            href = location.href.split('=');
                            url = '/office/blacklist/getDoneBlacklists?page='+ href[href.length-1];
                        }
                        $.ajax({
                            type:'GET',
                            url:url,
                            dataType:'json',
                            success:function (data) {
                                vm.blacklists = data.blacklists.data;
                                pageList(data.blacklists,'http://localhost/office/blacklist/notice'); // 构造分页按钮列表
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