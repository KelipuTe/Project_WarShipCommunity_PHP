<!--forum/show.blade.php中用户关注讨论的Vue.js组件-->
<template>
    <div>
        <button class="btn" :class="vclass" @click="userDiscussionFollow()">
            <span class="glyphicon " :class="vglyphicon"></span>
            <span v-text="vtext"></span>
        </button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component user-discussion-button Component mounted.')
        },
        data:function () {
            return{
                userDiscussion:0
            }
        },
        created:function () {
            this.userDiscussion = this.hasUserDiscussionFollow();
        },
        methods:{
            hasUserDiscussionFollow:function () {
                var vm = this;//这里需要指定是Vue.js的this不是JavaScript的this
                var discussion_id = $('#discussion-id').attr('name');
                $.ajax({
                    type:'GET',
                    url:'/follow/hasUserDiscussionFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userDiscussion = data.userDiscussion;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            },
            userDiscussionFollow:function () {
                var vm = this;
                var discussion_id = $('#discussion-id').attr('name');
                $.ajax({
                    type:'GET',
                    url:'/follow/userDiscussionFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userDiscussion = data.userDiscussion;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            }
        },
        computed:{
            vclass:function () {
                return this.userDiscussion ? 'btn-success' : 'btn-danger';
            },
            vglyphicon:function () {
                return this.userDiscussion ? 'glyphicon-star' : 'glyphicon-star-empty';
            },
            vtext:function () {
                return this.userDiscussion ? '已关注' : '关注该讨论';
            }
        }
    }
</script>