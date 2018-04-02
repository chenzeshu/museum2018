<template>
    <div class="troupe">
        <div class="topbar">
            <div class="topbar-item">
                <Button type="primary" size="small" class="topbar-item-add" @click="toggleAddModal">
                    <Icon type="android-add-circle" style="margin-right:8px"></Icon>新增
                </Button>
                <Button type="ghost" size="small" class="topbar-item-add" @click="toggleSearchModal">
                    <Icon type="search" style="margin-right:8px"></Icon>筛选
                </Button>
            </div>
            <div class="topbar-item">
                数量： <b><h4 style="display:inline-block">{{count}}</h4></b>
            </div>
        </div>

        <Table :loading="loading" :width="tableWidth" border :columns="columns" :data="tableData" highlight-row
               style="margin-left:8px"></Table>

        <div class="page">
            <Page :total="count" :page-size="pageSize" show-elevator
                  @on-change="pageTurning"></Page>
        </div>

        <!--检索-->
        <Modal
                v-model="searchFlag"
                title="检索"
                @on-ok="search"
        >
            <Form ref="searchForm" :model="searchObj"  :label-width="80">
                <FormItem label="剧团名称">
                    <Input placeholder="模糊名称" v-model="searchObj.troupe_name" style="width:160px"></Input>
                </FormItem>
                <FormItem label="彩蛋：演员个数">
                </FormItem>
                <FormItem label="彩蛋：演出个数">
                </FormItem>
            </Form>
        </Modal>
        <!--新增-->
        <Modal
                v-model="addFlag"
                title="新增"
                @on-ok="add"
        >
            <Form ref="addForm" :model="addObj"  :label-width="80">
                <FormItem label="剧团名称">
                    <Input v-model="addObj.troupe_name" style="width:200px"></Input>
                </FormItem>
            </Form>
        </Modal>
        <!--修改-->
        <Modal
                v-model="editFlag"
                title="修改"
                @on-ok="edit"
        >
            <Form ref="editForm" :model="editObj"  :label-width="80">
                <FormItem label="剧团名称">
                    <Input v-model="editObj.troupe_name" style="width:200px"></Input>
                </FormItem>
            </Form>
        </Modal>

        <!--删除-->
        <Modal
                v-model="deleteFlag"
                title="删除"
                @on-ok="destroy">
            <Alert type="error">确定删除？</Alert>
        </Modal>
    </div>
</template>

<script>
    import dataAndPageMixin from '@mixins/dataAndPageMixin.js'
    import curdMixin from '@mixins/curdMixin.js'
    import defaultPathMixin from '@mixins/defaultPathMixin.js'
    export default {
        mixins: [dataAndPageMixin, curdMixin, defaultPathMixin],
        name: "troupe",
        data(){
            return {
                name: "troupe",
                //检索
                searchFlag:false,
                searchObj:{},
                columns: [
                    {
                        title: '序号',
                        key: 'troupe_id',
                        width: 80,
                        fixed: 'left',
                        render: (h, params) => {
                            return h('span', ++params.index)
                        }
                    },
                    {
                        title: '剧团名称',
                        key: 'troupe_name',
                        width: 580,
                        fixed: 'left'
                    },
                    {
                        title: '操作',
                        width: 300,
                        align: 'center',
                        fixed: 'right',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '10px'
                                    },
                                    on: {
                                        click: () => {
                                            this.toggleEditModal(params.row, params.index)
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.toggleDeleteModel(params.row.troupe_id)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ]
            }
        },
        mounted(){
            this.fetchData('page')
            this.setDefaultPathAndCatalog()
        },
        methods:{
            toggleSearchModal(){
                this.searchFlag = !this.searchFlag
            },
            search(){
                this.searchCondition = Object.assign({}, this.searchObj)
                this.fetchData('page')
            }
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    .troupe
        .topbar
            height 32px
            margin 8px 0;
            padding-left 16px
            display flex
            flex-direction row
            justify-content:baseline
            align-items center
            .topbar-item
                width 260px
                .topbar-item-add
                    display inline-block
                    width 30%
                    &:nth-child(2)
                        margin-left 10px
        .page
            width 55%
            margin 28px auto 0 auto
</style>