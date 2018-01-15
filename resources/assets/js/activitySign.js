/**
 * Created by KelipuTe on 2017/11/22.
 */

window.Vue = require('vue');

/* 显示每日签到日历的 Vue.js 组件 */
Vue.component('show-sign', require('./components/ShowSign.vue'));

const activityShowSign = new Vue({
    el: '#showSign'
});