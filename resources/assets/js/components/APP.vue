<template>
    <div class="container">
        <Home class="test" v-if="flagLogin"></Home>
        <Login v-else></Login>
    </div>
</template>

<script >
    import Login from './login/login'
    import Home from './home/index/index'
    import {mapGetters, mapMutations} from 'vuex'
    import storage from 'good-storage'
    import {Base64} from '../utils/base64'

    export default {
        data(){
            return {
            }
        },
        mounted() {
            this.checkExp() //todo 检查token是否过期
        },
        computed: {
            ...mapGetters([
                'flagLogin'
            ])
        },
        methods:{
            checkExp(){
                let token = storage.get('token').replace(/Bearer /, "").split(".")[1],
                    exp = new Base64().decode(token).replace("{","").replace("}","").split(",")[3].slice(-10)*1000,
                    now = Date.now()
                if(exp < now){
                    // 已经过期
                    this.changeFlagLogin(false)
                }else{
                    // 为过期
                    this.changeFlagLogin(true)
                }
            },
            test(){
                this.$http.get('/test')
                    .then(res=>{
                        console.log(res)
                    }, err=>{
                        console.log(err)
                    })
            },
            ...mapMutations({
                changeFlagLogin:'CHANGE_FLAG_LOGIN'
            })
        },
        components:{
            Login, Home
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    @import "../../stylus/params.styl"
    .container
        height 100vh
        width 100vw
        margin 0
        padding 0
        background $color-homebackground
</style>
