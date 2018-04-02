import Vue from 'vue'
import router from './router'
import store from './store'
import axios from './axios'
import iView from 'iview'
import 'iview/dist/styles/iview.css';
import storage from 'good-storage'
// import VueLazyload from 'vue-lazyload'

// Vue.use(VueLazyload, {
//     lazyComponent:true
// })

Vue.use(iView)
window.Vue = Vue
Vue.prototype.$http = axios

Vue.component('app', require('./components/APP.vue'));

export const app = new Vue({
    el: '#app',
    router,
    store
});

//默认首页/刷新首页
let defaultPath = storage.get('defaultPath') || 'performance'
router.push({path: defaultPath})

