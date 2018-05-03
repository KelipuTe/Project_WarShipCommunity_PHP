@extends('user.notification.app')
@section('notification')
    <div id="from-person-letter">
        <from-person-letter></from-person-letter>
    </div>
    <template id="template-from-person-letter">
        <div>
            <div class="panel-heading">用户私信</div>
            <div class="panel-body">
                <div v-for="personalLetter in personalLetters">
                    <div class="col-md-4" style="padding: 0 20px">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <span>发给：@{{ personalLetter.to_user_username }}</span>
                            </div>
                            <div class="panel-body" data-toggle="modal" data-target="#showModal" @click="showModal(personalLetter.id)">
                                <p style="height:100px;overflow:hidden;word-wrap:break-word;text-overflow:ellipsis;" :id="['personalLetter-'+personalLetter.id]">
                                    @{{ personalLetter.body }}
                                </p>
                            </div>
                            <div class="panel-footer">@{{ personalLetter.created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="tagsModalLabel">详细内容</h4>
                        </div>
                        <p style="word-wrap:break-word;text-overflow:ellipsis;margin: 30px">
                            @{{ personalLetterBody }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        Vue.component('from-person-letter',{
            template:"#template-from-person-letter",
            data:function () {
                return{
                    personalLetters:'',
                    personalLetterBody:''
                }
            },
            created:function () {
                this.getFromPersonalLetter();
            },
            methods:{
                getFromPersonalLetter:function () {
                    var vm = this;
                    $.ajax({
                        type:'GET',
                        url:'/notification/getFromPersonalLetter',
                        dataType:'json',
                        success:function (data) {
                            vm.personalLetters = data.personalLetters;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                showModal:function (personalLetter_id) {
                    this.personalLetterBody = $('#personalLetter-'+personalLetter_id).text();
                }
            }
        });
        new Vue({
            el:"#from-person-letter"
        });
    </script>
@stop