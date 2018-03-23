<template>
    <div class="module-performance">
        <Table :loading="loading" :width="tableWidth" border :columns="columns" :data="tableData"></Table>

        <!--演员详情-->
        <Modal
                v-model="actorDetailFlag"
                title="主要演员">
            <p><b>人数</b>：{{this.actorData.length}}
                | <b>男性人数</b>：{{_countSex(this.actorData, "男")}}
                | <b>女性人数</b>：{{_countSex(this.actorData, "女")}}
            </p>
            <br>
            <Button type="ghost" size="small" style="margin-right:5px"
                    v-for="(actor, actorIndex) of actorData" :key="actorIndex">
                {{actor.actor_name}} : {{actor.actor_sex}}
            </Button>
        </Modal>
        <!--演出详情-->
        <Modal
                v-model="perfDetailFlag"
                title="演出详情">
            <p><b>演出内容</b>：{{perfDetail.perf_content}}</p>
            <br>
            <p><b>接收记录</b>：{{perfDetail.perf_receive}}</p>
            <br>
            <p><b>输出记录</b>：{{perfDetail.perf_output}}</p>
        </Modal>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex'
    export default {
        name: "performance",
        data(){
            return {
                //基础数据
                name: "performance",
                page: 1,
                pageSize: 10,
                loading: false,
                tableWidth: 1080,   //默认表格宽度
                //模态框
                    //演员详情
                    actorDetailFlag: false,
                    actorData: [],
                    //演出详情
                    perfDetailFlag: false,
                    perfDetail: {},
                columns: [
                    {
                        title: '编号',
                        key: 'perf_code',
                        width: 120,
                        fixed: 'left'
                    },
                    {
                        title: '演出日期',
                        key: 'perf_date',
                        width: 180,
                        fixed: 'left'
                    },
                    {
                        title: '演出剧种',
                        key: 'perf_type',
                        width: 180,
                        render: (h, params) => {
                            if(params.row.perf_type){
                                return h('span', params.row.perf_type.type_name)
                            }
                        }
                    },
                    {
                        title: '演出剧团',
                        key: 'perf_troupe',
                        width: 100,
                        render: (h, params) => {
                            if(params.row.perf_troupe){
                                return h('span', params.row.perf_troupe.troupe_name)
                            }
                        }
                    },
                    {
                        title: '演出地点',
                        key: 'perf_address',
                        width: 100,
                        render: (h, params) => {
                            if(params.row.perf_addr){
                                return h('span', params.row.perf_addr.addr_name)
                            }
                        }
                    },
                    {
                        title: '主要演员',
                        width: 100,
                        render: (h, params)=>{
                            return h('Button', {
                                props: {
                                    type: 'ghost',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.toggleActorDetail(params.row.perf_actors)
                                    }
                                }
                            }, '查看演员')
                        }
                    },
                    {
                        title: '演出详情',
                        width: 100,
                        render: (h, params)=>{
                            return h('Button', {
                                props: {
                                    type: 'ghost',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.togglePerfDetail(params.row.perf_detail)
                                    }
                                }
                            }, '查看详情')
                        }
                    },
                    {
                        title: '视频长度（分）',
                        key: 'perf_duration',
                        width: 100,
                    },
                    {
                        title: '视频大小（MB）',
                        key: 'perf_size',
                        width: 100,
                    },
                    {
                        title: '操作',
                        width: 150,
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
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {

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
            //调整表格大小
            this.resetTableWidth()
            //获取数据
            this.fetchData('page')
        },
        computed:{
            ...mapGetters([
                'tableData'
            ])
        },
        methods:{
            //性别统计
            _countSex(data, sex){
                return data.filter(item=>{
                    return item.actor_sex === sex
                }).length
            },
            toggleActorDetail(data){
                this.actorDetailFlag = !this.actorDetailFlag
                this.actorData = data
            },
            togglePerfDetail(data){
                this.perfDetailFlag = !this.perfDetailFlag
                this.perfDetail = data
            },
            resetTableWidth(){
                let curComponentWidth = document.querySelectorAll("div[class='module-performance']")[0].clientWidth
                if(curComponentWidth > 1400){
                    this.tableWidth = 1250
                }else{
                    this.tableWidth = curComponentWidth * 0.8
                }
            },
            fetchData(api){
                this.loading = true
                this.$http
                    .post(`${this.name}/${api}`, {
                        page: this.page,
                        pageSize: this.pageSize,
                    })
                    .then(res=>{
                        this.loading = false
                        res = res.data
                        this.$Message.success(res.msg)
                        this.setTableData(res.data.data)
                    })
            },
            ...mapMutations({
                setTableData:'SET_TABLE_DATA'
            })
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">

</style>