@extends('user.center.app')
@section('center')
    <div class="panel-heading">信息总览</div>
    <div class="panel-body">
        <div id="user-info">
            <user-info></user-info>
        </div>
        <template id="template-user-info">
            <div>
                <div class="media col-md-10 col-md-offset-1">
                    <div class="media-left">
                        <img src="" :src="user.avatar" class="media-object img-circle img-avatar-middle" alt="50x50">
                    </div>
                    <div class="media-body" style="padding-left: 50px">
                        <h3 class="row row-interval media-heading">
                            <span>@{{ user.username }}</span>
                            <span class="label label-info">正式会员</span>
                        </h3>
                        <div class="row row-interval">
                            <div class="progress col-md-7">
                                <div class="progress-bar" role="progressbar" id="liveness-progress" style="width:0">
                                    Level @{{ account_relatedInfo.level }}
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-1">
                                @{{ account.liveness }} / @{{ account_relatedInfo.nextLevel }}
                            </div>
                        </div>
                        <div class="row">
                            积分：@{{ account.bonus_points }}
                        </div>
                    </div>
                    <hr class="col-md-12">
                </div>
                <div class="row col-md-10 col-md-offset-1">
                    <div class="col-md-3 thumbnail-interval" v-for="role in roles">
                        <div class="thumbnail">
                            <div class="caption">
                                <h3 class="text-center">
                                    @{{ role.label }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <hr class="col-md-12">
                </div>
            </div>
        </template>
        <script>
            Vue.component('user-info',{
                template:"#template-user-info",
                data:function () {
                    return {
                        user: '',
                        account: '', account_relatedInfo: '',
                        roles: ''
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
                            url: '/api/user/center/getUserInfo',
                            dataType:'json',
                            success:function (data) {
                                vm.user = data.data.user;
                                vm.account = data.data.account;
                                vm.account_relatedInfo = data.data.account.relatedInfo;
                                vm.roles = data.data.roles;
                                $('#liveness-progress')
                                    .attr('style','width:'+ vm.account.liveness / vm.account_relatedInfo.nextLevel * 100 +'%')
                            }
                        });
                    }
                }
            });
            new Vue({ el:"#user-info" });
        </script>
    </div>
@stop