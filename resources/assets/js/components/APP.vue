<template>
    <div class="container">
        <Home v-if="flagLogin"></Home>
        <Login v-else></Login>
    </div>
</template>

<script >
    import Login from './login/login'
    import Home from './home/index/index'
    import {mapGetters, mapMutations} from 'vuex'
    import storage from 'good-storage'
    import {decode} from "../utils/base64";

    export default {
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
                let token = storage.get('token'),
                    exp, now
                if(!token){
                    this.changeFlagLogin(false)
                }else{
                    token = token.replace(/Bearer /, "").split(".")[1]
                    token = JSON.parse(decode(token))
                    exp =  token.exp*1000
                    now = Date.now()
                    if(exp < now){
                        // 已经过期
                        this.changeFlagLogin(false)
                    }else{
                        // 为过期
                        this.changeFlagLogin(true)
                    }
                }
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
