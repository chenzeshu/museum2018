<template>
    <div class="module-performance">
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
        <!--分页-->
        <div class="page">
            <Page :total="count" :page-size="pageSize" show-elevator
                  @on-change="pageTurning"></Page>
        </div>
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
            <br>
            <p><b>其他备注</b>：{{perfDetail.perf_remark}}</p>
        </Modal>
        <!--检索-->
        <Modal
                v-model="searchFlag"
                title="检索"
                @on-ok="search"
        >
            <Form ref="searchForm" :model="searchObj"  :label-width="80">
                <FormItem label="演出日期">
                    <DatePicker :value="searchObj.perf_date" format="yyyy/MM/dd" type="daterange" placement="bottom-end"
                                placeholder="Select date" style="width: 200px"
                                @on-change="_selectDateForSearch"
                                @on-clear="_clearDateForSearch"></DatePicker>
                </FormItem>
                <FormItem label="剧种">
                    <Select v-model="searchObj.perf_type.type_id" style="width:200px" :clearable="true" ref="perfType">
                        <Option v-for="(type, index) of types" :value="type.type_id" :key="index">{{ type.type_name }}</Option>
                    </Select>
                    <span class="select-clear-button" @click="clearPerfType">
                        <Icon type="close-circled"></Icon>
                    </span>
                </FormItem>
                <FormItem label="剧团">
                    <Select v-model="searchObj.perf_troupe.troupe_id" style="width:200px"  :clearable="true" ref="perfTroupe">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{ troupe.troupe_name }}</Option>
                    </Select>
                    <span class="select-clear-button" @click="clearPerfTroupe">
                        <Icon type="close-circled"></Icon>
                    </span>
                </FormItem>
                <FormItem label="演出地点">
                    <Select v-model="searchObj.perf_addr.addr_id" style="width:200px"  :clearable="true" ref="perfAddr">
                        <Option v-for="(addr, index) of addrs" :value="addr.addr_id" :key="index">{{ addr.addr_name }}</Option>
                    </Select>
                    <span class="select-clear-button" @click="clearPerfAddr">
                        <Icon type="close-circled"></Icon>
                    </span>
                </FormItem>
                <FormItem label="演员">
                    <Select
                            v-model="searchObj.perf_actors"
                            filterable
                            remote
                            multiple
                            :remote-method="searchActors"
                            :loading="fetchActorsLoading"
                            :loading-text="fetchActorsLoadingText"
                    >
                        <Option v-for="(actor, index) of actors" :value="actor.actor_id" :key="index">{{actor.actor_name}}</Option>
                    </Select>
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
                <FormItem label="演出日期">
                    <DatePicker type="datetime" placeholder="Select date and time" style="width: 200px" :value="addObj.perf_date"
                                @on-change="_selectDateForAdd"></DatePicker>
                </FormItem>
                <FormItem label="剧种">
                    <Select v-model="addObj.perf_type.type_id" style="width:200px">
                        <Option v-for="(type, index) of types" :value="type.type_id" :key="index">{{ type.type_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="剧团">
                    <Select v-model="addObj.perf_troupe.troupe_id" style="width:200px">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{ troupe.troupe_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="演出地点">
                    <Select v-model="addObj.perf_addr.addr_id" style="width:200px">
                        <Option v-for="(addr, index) of addrs" :value="addr.addr_id" :key="index">{{ addr.addr_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="演员">
                    <Select
                            v-model="addObj.perf_actors"
                            filterable
                            remote
                            multiple
                            :remote-method="searchActors"
                            :loading="fetchActorsLoading"
                            :loading-text="fetchActorsLoadingText"
                    >
                        <Option v-for="(actor, index) of actors" :value="actor.actor_id" :key="index">{{actor.actor_name}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="选填信息">
                    <i-switch v-model="addAlterFlag" size="large" @click="_toggleAddAlterFlag">
                        <span slot="open">On</span>
                        <span slot="close">Off</span>
                    </i-switch>
                </FormItem>
                <div v-if="addAlterFlag">
                    <FormItem label="演出内容">
                        <Input v-model="addObj.perf_content" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="接收记录">
                        <Input v-model="addObj.perf_receive" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="输出记录">
                        <Input v-model="addObj.perf_output" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="其他备注">
                        <Input v-model="addObj.perf_remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="视频长度">
                        <Input type="text" v-model="addObj.perf_duration" number></Input>
                    </FormItem>
                    <FormItem label="视频大小">
                        <Input type="text" v-model="addObj.perf_size" number></Input>
                    </FormItem>
                    <FormItem label="备份类型">
                        <CheckboxGroup v-model="addObj.perf_baktypes">
                            <Checkbox  :label="baktype.baktype_id"  v-for="(baktype, index) of baktypes" :key="index">
                                <span>{{ baktype.baktype_name }}</span>
                            </Checkbox>
                        </CheckboxGroup>
                    </FormItem>
                </div>
            </Form>
        </Modal>
        <!--修改-->
        <Modal
                v-model="editFlag"
                title="修改"
        @on-ok="edit">
            <Form ref="editForm" :model="editObj"  :label-width="80"
                  v-if="!!editObj && editObj.perf_type && editObj.perf_troupe && editObj.perf_addr">
                <FormItem label="演出编号">
                    <Input type="text" v-model="editObj.perf_code"></Input>
                </FormItem>
                <FormItem label="演出日期">
                    <DatePicker type="datetime" placeholder="Select date and time" style="width: 200px" :value="editObj.perf_date"
                                @on-change="_selectDateForEdit"></DatePicker>
                </FormItem>
                <FormItem label="剧种">
                    <Select v-model="editObj.perf_type.type_id" style="width:200px">
                        <Option v-for="(type, index) of types" :value="type.type_id" :key="index">{{ type.type_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="剧团">
                    <Select v-model="editObj.perf_troupe.troupe_id" style="width:200px">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{ troupe.troupe_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="演出地点">
                    <Select v-model="editObj.perf_addr.addr_id" style="width:200px">
                        <Option v-for="(addr, index) of addrs" :value="addr.addr_id" :key="index">{{ addr.addr_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="演员">
                    <Select
                            v-model="editObj.actors"
                            :label="editActorsLabel"
                            filterable
                            remote
                            multiple
                            :remote-method="searchActors"
                            :loading="fetchActorsLoading"
                            :loading-text="fetchActorsLoadingText"
                    >
                        <Option v-for="(actor, index) of actors" :value="actor.actor_id" :key="index">{{actor.actor_name}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="选填信息">
                    <i-switch v-model="editAlterFlag" size="large" @click="_toggleEditAlterFlag">
                        <span slot="open">On</span>
                        <span slot="close">Off</span>
                    </i-switch>
                </FormItem>
                <div v-if="editAlterFlag">
                    <FormItem label="演出内容">
                        <Input v-model="editObj.perf_content" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="接收记录">
                        <Input v-model="editObj.perf_receive" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="输出记录">
                        <Input v-model="editObj.perf_output" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="其他备注">
                        <Input v-model="editObj.perf_remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="写点什么..."></Input>
                    </FormItem>
                    <FormItem label="视频长度">
                        <Input type="text" v-model="editObj.perf_duration" number></Input>
                    </FormItem>
                    <FormItem label="视频大小">
                        <Input type="text" v-model="editObj.perf_size" number></Input>
                    </FormItem>
                    <FormItem label="备份类型">
                        <CheckboxGroup v-model="editObj.perf_baktypes">
                            <Checkbox  :label="baktype.baktype_id"  v-for="(baktype, index) of baktypes" :key="index">
                                <span>{{ baktype.baktype_name }}</span>
                            </Checkbox>
                        </CheckboxGroup>
                    </FormItem>
                </div>
            </Form>
        </Modal>
        <!--删除-->
        <Modal
            v-model="deleteFlag"
            title="删除"
            @on-ok="destroy">
            <Alert type="error">确定删除？</Alert>
        </Modal>
        <!--编辑照片-->
        <PhotoModal
            :photoFlag="photoFlag"
            :uploadPhotoList="defaultPhotoList"
            @on-confirm="onConfirm"
            @on-flag="onPhotoFlag"
        ></PhotoModal>
    </div>
</template>

<script>
    import objUtils from '@utils/objUtils'
    import httpUtils from '@utils/httpUtils'
    import PhotoModal from '@base/PhotoModal/PhotoModal'

    import photoMixin from '@mixins/photoMixin.js'
    import dataAndPageMixin from '@mixins/dataAndPageMixin.js'
    import curdMixin from '@mixins/curdMixin.js'
    import defaultPathMixin from '@mixins/defaultPathMixin.js'

    import { filterSelect } from "./methodForSelect";

    var interval;
    export default {
        mixins: [photoMixin, dataAndPageMixin, curdMixin, defaultPathMixin],
        name: "performance",
        data(){
            return {
                //基础数据
                name: "performance",
                //模态框
                    //演员详情
                    actorDetailFlag: false,
                    actorData: [],
                    //演出详情
                    perfDetailFlag: false,
                    perfDetail: {},
                //检索
                    searchFlag: false,
                    searchObj: this._resetAddObj(),
                    searchCondition: "", //作为fetchData接口的成员
                //获取演员
                    fetchActorsLoading: false,
                    fetchActorsLoadingText: `2秒后开始搜索...`,
                    actors: [],     //存放每次检索后端传来的数据
                    query: "",
                    delay: 2000, //延迟时间
                    still: 2000,  //剩余确认时间
                    editActorsLabel: [],   //用于存放名字
                //工具类数据
                    types: [],
                    troupes: [],
                    addrs: [],
                    baktypes: [],
                columns: [
                    {
                        title: '编号',
                        key: 'perf_code',
                        width: 120,
                        fixed: 'left',
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
                        key: 'perf_addr',
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
                        title: '备份类型',
                        key: 'perf_baktypes',
                        width: 160,
                        render: (h, params) => {
                            if(!params.row.perf_baktypes) return;
                            else if (
                                params.row.perf_baktypes.filter(item => parseInt(item.baktype_id) === 1).length === 1
                            ) {
                                return h('span',  [
                                            h('Button', {
                                                props: {
                                                    type:  'warning',
                                                    size: 'small'
                                                }
                                            }, params.row.perf_baktypes[0].baktype_name)
                                        ])
                            } else {
                                let foo = []
                                for (let value of params.row.perf_baktypes) {
                                    foo.push(h('span',  [
                                        h('Button', {
                                            props: {
                                                type:  'success',
                                                size: 'small'
                                            },
                                            style: {
                                                marginRight: '8px'
                                            },
                                        }, value.baktype_name)
                                    ]))
                                }
                                return foo
                            }
                        }
                    },
                    {
                        title: '操作',
                        width: 180,
                        align: 'center',
                        fixed: 'right',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '8px'
                                    },
                                    on: {
                                        click: () => {
                                            this.togglePhotoModal(params.row)
                                        }
                                    }
                                }, '照片'),
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
        created(){
            //todo create 不应该做dom操作， 否则，就需要this.$nextTick()
            this.$watch('query', httpUtils.debounce((newQuery) => {
                this.searchActorsDebounce(newQuery)
            },this.delay))
        },
        mounted(){
            //获取数据
            this.fetchData('page')
            this.setDefaultPathAndCatalog()
            //获取工具数据：剧团、剧种、演出地点
            setTimeout(()=>{
                this.fetchBaseData()
            },1000)
        },
        destroyed(){
            //卸载本地工具类缓存
            //卸载轮询器
            clearInterval(interval)
        },
        methods:{
            ...filterSelect,
            //检索
            toggleSearchModal(){
                this.searchFlag = !this.searchFlag
            },
            _selectDateForSearch(v){
                this.searchObj.perf_date = v
            },
            _clearDateForSearch(){
                Reflect.deleteProperty(this.searchObj, 'perf_date')
            },
            search(){
               let body = Object.assign({}, this.searchObj)
                     body = this._pakNormalBody(body)
                this.searchCondition = body
                this.fetchData('page')
            },
            //todo curd自属
            //组装add的通讯用的body
            _pakAddBody(){
                let body = objUtils.deepClone(this.addObj)
                body = this._pakNormalBody(body)
                this.addObj = this._resetAddObj()
                return body
            },
            _pakEditBody(){
                let body = objUtils.deepClone(this.editObj)
                    body = this._pakNormalBody(body)
                    Reflect.set(body, 'perf_actors', body.actors)

                let deleteKeyArr = ['perf_detail', '_index', '_rowKey', 'actors', 'perf_files']
                deleteKeyArr.forEach(key => {
                    Reflect.deleteProperty(body, key)
                })
                return body
            },
                //pakAdd和pakEdit都用到的
                _pakNormalBody(body){
                    Reflect.set(body, 'perf_type', body.perf_type.type_id)
                    Reflect.set(body, 'perf_troupe', body.perf_troupe.troupe_id)
                    Reflect.set(body, 'perf_addr', body.perf_addr.addr_id)
                    Reflect.set(body, 'perf_baktypes', body.perf_baktypes)
                    return body
                },
            //重置addObj，复用在data定义和http通讯两个地方
            _resetAddObj(){
                return {
                    perf_troupe: {troupe_id:""},
                    perf_type:{type_id:""},
                    perf_addr:{addr_id:""},
                    perf_actors: [],
                    perf_baktypes: []
                }
            },
            _selectDateForAdd(v){
                this.addObj.perf_date = v
            },
            _selectDateForEdit(date){
                this.editObj.perf_date = date
            },
            _toggleAddAlterFlag(){
                this.addAlterFlag = !this.addAlterFlag
            },
            _toggleEditAlterFlag(){
                this.editAlterFlag = !this.editAlterFlag
            },
            //Actor的select组件默认值初始化
            _initActorsValue(){
                this.editObj.actors = this.editObj.perf_actors.map(actor=>{
                    return actor.actor_id
                })
                this.editActorsLabel = this.editObj.perf_actors.map(actor=>{
                    return actor.actor_name
                })
            },
            //修改edit模态框
            toggleEditModal(source, index){
                    this.editIndex = index
                    this.editFlag = !this.editFlag
                    this.editObj = objUtils.deepClone(source)
                    this.editObj.perf_baktypes = this.editObj.perf_baktypes.map(item => {
                        return item.baktype_id
                    })
                    this._initActorsValue()
                    this.editAlterFlag = false
            },
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

            //剧种、剧团、地址
            fetchBaseData(){
                this.$http
                    .get(`${this.name}/baseData`)
                    .then(res=>{
                        res = res.data.data
                        this.types = res.types
                        this.troupes = res.troupes
                        this.addrs = res.addrs
                        this.baktypes = res.baktypes
                    })
            },
            //获取演员
            searchActors(query){
                if(query !== ""){
                    this.searchCountDown()
                    this.fetchActorsLoading = true
                    this.query = query
                }
            },
            //描述倒计时
            searchCountDown(){
                if(interval){
                    clearInterval(interval)
                    //并reset
                    this.still = this.delay
                }
                interval = setInterval(()=>{
                    if(this.still > 0){
                        this.still = this.still - 100
                        this.fetchActorsLoadingText = `${this.still/1000}秒后开始搜索`
                    } else {
                        this.fetchActorsLoadingText = `已确认`
                        this.still = this.delay
                    }
                }, 100)
            },
            //搜索演员（配合watch-debounce）
            searchActorsDebounce(query){
                this.$http
                    .post('/actor/searchActors', {
                        query
                    })
                    .then( res =>{
                        this.fetchActorsLoading = false
                        this.actors = this.actors.concat(res.data.data)
                    }, err=>{
                        this.fetchActorsLoading = false
                    })
            }
        },
        components: {
            PhotoModal
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    @import "../../../../stylus/forSelect.styl"
    .module-performance
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