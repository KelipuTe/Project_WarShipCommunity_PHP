@extends('user.center.app')
@section('center')
    <div class="panel-heading">我的账户</div>
    <div class="panel-body">
        <div id="account">
            <account></account>
        </div>
        <template id="template-account">
            <div>
                <div>积分：@{{ account.bonus_points }}</div>
                <div v-for="tool in tools">
                    <div v-if="tool.tool=='setTop'">置顶卡：@{{ tool.number }}</div>
                    <div v-if="tool.tool=='reSign'">补签卡：@{{ tool.number }}</div>
                    <button @click="exchangeTool(tool.tool)">使用积分兑换</button>
                </div>
            </div>
        </template>
        <script>
            Vue.component('account',{
                template:"#template-account",
                data:function () {
                    return {
                        account: '',
                        tools: ''
                    };
                },
                created:function () {
                    this.init();
                },
                methods:{
                    init:function(){
                        var vm = this;
                        $.ajax({
                            type: 'get',
                            url: '/account/getTools',
                            dataType:'json',
                            success:function (data) {
                                vm.account = data.account;
                                vm.tools = data.tools;
                            },
                            error:function(jqXHR){
                                console.log("出现错误：" +jqXHR.status);
                            }
                        });
                    },
                    exchangeTool:function(tool){
                        var vm = this;
                        $.ajax({
                            type: 'post',
                            url: '/account/exchangeTool',
                            data: { 'tool' : tool },
                            dataType:'json',
                            success:function (data) {
                                if(data.status == 'success') {
                                    vm.account = data.account;
                                    vm.tools = data.tools;
                                } else {
                                    alert(data.message);
                                }
                            },
                            error:function(jqXHR){
                                console.log("出现错误：" +jqXHR.status);
                            }
                        });
                    }
                }
            });
            new Vue({ el:"#account" });
        </script>
    </div>
@stop