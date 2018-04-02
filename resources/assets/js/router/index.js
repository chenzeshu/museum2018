import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

//组件
import Performance from '../components/home/performance/performance'
import Actor from '../components/home/actor/actor'
import Addr from '../components/home/others/addr/Addr'
import Troupe from '../components/home/others/troupe/Troupe'
import Type from '../components/home/others/type/Type'
import Baktype from '../components/home/others/baktype/Baktype'
import User from '../components/home/manager/user/User'


export default new VueRouter({
    routes: [
        {
            path: '/performance',
            component: Performance
        },
        {
            path: '/actor',
            component: Actor
        },
        {
            path: '/addr',
            component: Addr
        },
        {
            path: '/baktype',
            component: Baktype
        },
        {
            path: '/troupe',
            component: Troupe
        },
        {
            path: '/type',
            component: Type
        },
        {
            path: '/user',
            component: User
        },
    ],
})

