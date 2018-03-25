<template>
    <div class="layout home">
        <!--注销-->
        <transition name="move">
            <div class="topbar-right">
                <span class="topbar-right-logout" @click="logout">注销</span>
            </div>
        </transition>

        <Layout>
            <Sider ref="side1" hide-trigger collapsible :collapsed-width="78" v-model="isCollapsed" style="min-height:99.5vh">
                <Menu :active-name="activeName" :open-names="openNames" theme="dark" width="auto" :class="menuitemClasses"
                      accordion>
                    <Submenu v-for="(catalog, index) of catalogs" :key="index" :name="catalog.name" ref="subMenu">
                        <template slot="title" v-show="!isCollapsed">
                            <Icon :type="catalog.iconType"></Icon>
                            <span>{{catalog.title}}</span>
                        </template>
                        <MenuItem :name="menu.name" v-for="(menu, menuIndex) of catalog.menus" :key="menuIndex"
                                  @click.native="navigateTo(menu.path)">
                            <Icon :type="menu.icon" v-if="menu.icon" class="menu-icon" ></Icon>{{menu.title}}
                        </MenuItem>
                    </Submenu>
                </Menu>
            </Sider>
            <Layout>
                <Header :style="{padding: 0}" class="layout-header-bar">
                    <Icon @click.native="collapsedSider" :class="rotateIcon" :style="{margin: '20px 20px 0'}" type="navicon-round" size="24"></Icon>
                </Header>
                <Content :style="{margin: '20px', background: '#fff', minHeight: '260px'}">
                    <router-view></router-view>
                </Content>
            </Layout>
        </Layout>
    </div>
</template>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    @import "~@style/params"

    .layout
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    .topbar-right
        position fixed
        left 254px
        top 20px
        width 200px
        height 32px
        font-size 18px
        cursor pointer
        .topbar-right-logout:hover
            color: $color-button
    .layout-header-bar
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
    .layout-logo-left
        width: 90%;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        margin: 15px auto;
    .menu-icon
        transition: all .3s;
    .rotate-icon
        transform: rotate(-90deg);
    .menu-item span
        display: inline-block;
        overflow: hidden;
        width: 69px;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
        transition: width .2s ease .2s;
    .menu-item i
        transform: translateX(0px);
        transition: font-size .2s ease, transform .2s ease;
        vertical-align: middle;
        font-size: 16px;
    .menu-item .menu-icon
        display inline-block
        width 16px
        text-align center
        margin-right:5px
        margin-bottom:2px
    .collapsed-menu span
        width: 0px;
        transition: width .2s ease;
    .collapsed-menu i
        transform: translateX(5px);
        transition: font-size .2s ease .2s, transform .2s ease .2s;
        vertical-align: middle;
        font-size: 22px;
</style>

<script>
    import {mapGetters, mapMutations} from 'vuex'
    import storage from 'good-storage'
    import {catalogs} from './catalogs'
    export default {
        data () {
            return {
                isCollapsed: false, //是否横向折叠
                activeName: "1-1",
                openNames: ['1'],
                catalogs: catalogs
            }
        },
        computed: {
            rotateIcon () {
                return [
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ];
            },
            menuitemClasses () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            },
            ...mapGetters([
                'flagLogin'
            ])
        },
        methods: {
            collapsedSider () {
                this.$refs.side1.toggleCollapse();
                //注销条也左移
                this._collasedBarTransition()
            },
            //注销条缩展动画
            _collasedBarTransition(){
                let topbarRight = document.querySelectorAll('.topbar-right')[0]
                topbarRight.style.transform = this.isCollapsed ? `translate3d(-122px,0,0)` : `translate3d(0,0,0)`
                topbarRight.style.transitionDuration = this.isCollapsed ? `.4s` : `.2s`
                topbarRight.style.transitionTimingFunction = `ease`
            },
            logout(){
                  this.changeFlagLogin(false)
                  storage.set('token', "")
            },
            //todo 跳转到，避免直接使用<router-link>导致文字颜色动效失效
            navigateTo(path){
                this.$router.push(path)
            },
            ...mapMutations({
                changeFlagLogin: 'CHANGE_FLAG_LOGIN'
            })
        },
    }
</script>