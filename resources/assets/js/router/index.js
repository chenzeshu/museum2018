import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

//组件
import Performance from '../components/home/performance/performance'
import home from '../components/home/index/index'
import Actor from '../components/home/actor/actor'

export default new VueRouter({
    routes: [
        {
            path: '/performance',
            component: Performance
        },
        {
            path: '/actor',
            component: Actor
        }
    ],
})

