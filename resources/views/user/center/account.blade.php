@extends('user.center.app')
@section('center')
    <div class="panel-heading">我的账户</div>
    <div class="panel-body">
        <div id="account">
            <account></account>
        </div>
        <template id="template-account">
            <div>
                <div class="col-md-4 col-md-offset-1" v-for="tool in tools">
                    <div class="thumbnail" v-if="tool.name=='setTop'">
                        <img alt="200 * 200" src="/image/background/zhidingka.jpg" style="height: 200px; width: 200px; display: block;">
                        <div class="caption">
                            <h4 class="text-center">
                                <span>置顶卡：@{{ tool.number }}</span>
                            </h4>
                            <h3 class="text-center">
                                <button class="btn btn-success" @click="exchangeTool(tool.name)">使用积分兑换</button>
                            </h3>
                        </div>
                    </div>
                    <div class="thumbnail" v-if="tool.name=='reSign'">
                        <img alt="200 * 200" src="/image/background/buqianka.jpg" style="height: 200px; width: 200px; display: block;">
                        <div class="caption">
                            <h4 class="text-center">
                                <span>补签卡：@{{ tool.number }}</span>
                            </h4>
                            <h3 class="text-center">
                                <button class="btn btn-success" @click="exchangeTool(tool.name)">使用积分兑换</button>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-control">积分：@{{ account.bonus_points }}</div>
                    </div>
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
                    exchangeTool:function(name){
                        var vm = this;
                        $.ajax({
                            type: 'post',
                            url: '/account/exchangeTool',
                            data: { 'name' : name },
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