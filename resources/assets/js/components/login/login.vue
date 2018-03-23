<template>
    <div class="module-login">
        <div class="login-wrapper">
            <div class="login-header">
                <h1>非遗</h1>
                <div class="login-slogen">
                    登陆非遗平台，管理更多的视频
                </div>
            </div>
            <div class="login-container">
                <div class="phone">
                    <input type="text" class="phone-input" placeholder="手机号" v-model="phone">
                </div>
                <div class="pass">
                    <input type="password" class="pass-input" placeholder="密码" v-model="password"
                    @keyup.enter="login">
                </div>
                <div class="others">
                    忘记密码？
                </div>
                <div class="submit">
                    <button class="submit-button" @click="login">登陆</button>
                </div>
                <div class="third-party">
                    <a href="#" @click="waiting($event)">二维码登陆</a><span> or </span><a href="#" @click="waiting($event)">社交账号登陆</a>
                </div>
            </div>
            <div class="login-switch">
                <span class="switch-word">没有账号？<span class="switch-to-reg">注册</span></span>
            </div>
        </div>
    </div>
</template>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    @import "../../../stylus/params.styl"

    .module-login
        height 40vh
        min-height 500px
        width 100vw
        margin auto 0
        display flex
        flex-direction column
        justify-content baseline
        align-items center
        .login-wrapper
            position fixed
            top 10vh
            height 50vh
            min-height 475px
            width 20vw
            min-width 380px
            border-radius 5px
            box-shadow: 0 0 8px 0 rgb(200,200,200);
            background #fff
            display flex
            flex-direction column
            justify-content:space-between
            align-items center
            .login-header
                width 80%
                padding 30px 0
                margin 0 auto
                text-align center
                color $color-logo
                h1
                    font-size 48px
                    margin-top: 16px;
                    margin-bottom: 2px;
                .login-slogen
                    font-size 18px
                    padding-top: 10px;
                    margin-bottom: -20px;
            .login-container
                width 100%
                margin 0 auto
                display flex
                flex-direction column
                justify-content baseline
                align-items center
                .phone
                    width 80%
                    .phone-input
                        width 100%
                        border none
                        border-bottom: 1px solid rgba(0,0,0,.2);
                        outline 0
                        padding 5px 10px
                        margin 15px 0
                .pass
                    width 80%
                    .pass-input
                        width 100%
                        border none
                        border-bottom: 1px solid rgba(0,0,0,.2);
                        outline 0
                        padding 5px 10px
                        margin 15px 0
                .others
                    align-self flex-end
                    padding 10px 30px 10px 0
                .submit
                    width 80%
                    .submit-button
                        width 100%
                        height 36px
                        line-height 36px
                        margin 0 auto
                        border none
                        border-radius 5px
                        color #fff
                        background $color-button
                        outline none
                        &:hover
                            background $color-button-active
                .third-party
                    width 80%
                    padding 15px 0
                    margin 0 auto
                    display flex
                    flex-direction row
                    justify-content:space-around
                    align-items:center
                    color #8590a6
                    a
                        color #8590a6
                        &:hover
                            text-decoration  none
                            color $color-button
            .login-switch
                width 100%
                background #ebebeb
                padding 12px 0
                margin 0 auto
                text-align center
                font-size 16px
                .switch-to-reg
                    color $color-button
                    font-weight 700
                    cursor pointer
</style>

<script>
    import {mapGetters, mapMutations} from 'vuex'

    export default {
        name: "login",
        data(){
            return {
                phone: "",
                password: ""
            }
        },
        computed:{
            ...mapGetters([
                'flagLogin'
            ])
        },
        methods:{
            login(){
                this.$http
                    .post('/login', {
                        phone: this.phone,
                        password: this.password
                    })
                    .then(res=>{
                        this.$Message.success(res.data.msg)
                        this.changeFlagLogin(true)
                    })
            },
            waiting(e){
                e.preventDefault()
              this.$Message.warning('等待...')
            },
            ...mapMutations({
                changeFlagLogin:'CHANGE_FLAG_LOGIN'
            })
        }
    }
</script>

