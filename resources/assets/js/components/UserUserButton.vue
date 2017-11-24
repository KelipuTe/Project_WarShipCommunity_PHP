<!--用户关注用户的Vue.js组件-->
<template>
    <div>
        <button class="btn btn-default" :class="vclass" @click="userUserFollow()">
            <span class="glyphicon " :class="vglyphicon"></span>
            <span v-text="vtext"></span>
        </button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component user-user-button mounted.')
        },
        data:function () {
            return{
                userUser:0
            }
        },
        created:function () {
            this.userUser = this.hasUserUserFollow();
        },
        methods:{
            hasUserUserFollow:function () {
                var vm = this;
                var discussion_id = $('#discussion-id').attr('name');
                $.ajax({
                    type:'GET',
                    url:'/follow/hasUserUserFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userUser = data.userUser;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            },
            userUserFollow:function () {
                var vm = this;
                var discussion_id = $('#discussion-id').attr('name');
                $.ajax({
                    type:'GET',
                    url:'/follow/userUserFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userUser = data.userUser;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            }
        },
        computed:{
            vclass:function () {
                return this.userUser ? 'btn-success' : '';
            },
            vglyphicon:function () {
                return this.userUser ? 'glyphicon-star' : 'glyphicon-star-empty';
            },
            vtext:function () {
                return this.userUser ? '已关注' : '关注TA';
            }
        }
    }
</script>