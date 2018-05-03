@extends('user.notification.app')
@section('notification')
    <div id="to-person-letter">
        <to-person-letter></to-person-letter>
    </div>
    <template id="template-to-person-letter">
        <div>
            <div class="panel-heading">用户私信</div>
            <div class="panel-body">
                <div v-for="personalLetter in personalLetters">
                    <div class="col-md-4" style="padding: 0 20px">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <span>来自：@{{ personalLetter.from_user_username }}</span>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#messageModal"
                                        @click="getTarget(personalLetter.from_user_id,personalLetter.from_user_username)">
                                    <span class="fa fa-paper-plane-o fa-lg"></span><span> 回复</span></button>
                            </div>
                            <!-- Button trigger modal -->
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
            <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="tagsModalLabel"> 给 <span v-text="toUsername"></span> 的回复 </h4>
                        </div>
                        <div class="modal-body">
                            <label for="message-body">内容：</label>
                            <textarea name="message-body" class="form-control" id="message-body" rows="5" style="resize: none"></textarea>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button id="message-submit" type="button" class="btn btn-primary" @click="sendMessage">
                                    <span class="fa fa-paper-plane-o fa-lg"></span> 发送
                                </button>
                            </div>
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
                            <h4 class="modal-title" id="tagsModalLabel"> 详细内容 </h4>
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
        Vue.component('to-person-letter',{
            template:"#template-to-person-letter",
            data:function () {
                return{
                    personalLetters:'',
                    toUserId:'',
                    toUsername:'',
                    personalLetterBody:''
                }
            },
            created:function () {
                this.getToPersonalLetter();
            },
            methods:{
                getToPersonalLetter:function () {
                    var vm = this;
                    $.ajax({
                        type:'GET',
                        url:'/notification/getToPersonalLetter',
                        dataType:'json',
                        success:function (data) {
                            vm.personalLetters = data.personalLetters;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                getTarget:function (toUserId,toUsername) {
                    this.toUserId = toUserId;
                    this.toUsername = toUsername;
                },
                sendMessage:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/notification/messageStore',
                        data:{
                            'to_user_id':vm.toUserId,
                            'body':$('#message-body').val()
                        },
                        dataType:'json',
                        success:function (data) {
                            alert(data.message);
                            $('#messageModal').modal('toggle');
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
            el:"#to-person-letter"
        });
    </script>
@stop