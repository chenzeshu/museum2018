<template>
    <div class="actor">
        <Table :loading="loading" :width="tableWidth" border :columns="columns" :data="tableData" highlight-row
               style="margin-left:8px"></Table>
    </div>
</template>

<script>
    import objUtils from '@utils/objUtils'
    import httpUtils from '@utils/httpUtils'
    import PhotoModal from '@base/PhotoModal/PhotoModal'

    import photoMixin from '@mixins/photoMixin.js'
    import dataAndPageMixin from '@mixins/dataAndPageMixin.js'
    import curdMixin from '@mixins/curdMixin.js'

    export default {
        mixins: [photoMixin, dataAndPageMixin, curdMixin],
        name: "actor",
        data(){
            return {
                name: "actor",
                tableWidth: 1080,   //默认表格宽度
                columns: [
                    {
                        title: 'ID',
                        key: 'actor_id',
                        width: 100,
                        fixed: 'left'
                    },
                    {
                        title: '演员姓名',
                        key: 'actor_name',
                        width: 180,
                        fixed: 'left'
                    },
                    {
                        title: '性别',
                        key: 'actor_sex',
                        width: 120,
                    },
                    {
                        title: '年龄',
                        key: 'actor_age',
                        width: 100,
                    },
                    {
                        title: '获奖经历',
                        key: 'actor_record',
                        width: 100,
                        render: (h, params) => {
                            return h('button', '查看获奖经历')
                        }
                    },
                    {
                        title: '剧团历史',
                        width: 150,
                        render: (h, params)=>{
                            return h('Button','查看所在剧团历史')
                        }
                    },
                    {
                        title: '操作',
                        width: 180,
                        align: 'center',
                        fixed: 'right',
                        render: (h, params) => {
                            return h('div', [
                                // h('Button', {
                                //     props: {
                                //         type: 'warning',
                                //         size: 'small'
                                //     },
                                //     style: {
                                //         marginRight: '8px'
                                //     },
                                //     on: {
                                //         click: () => {
                                //             this.togglePhotoModal(params.row)
                                //         }
                                //     }
                                // }, '照片'),
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '8px'
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
                                            this.toggleDeleteModel(params.row.perf_id)
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ]
            }
        },

    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">

</style>