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
                                @on-change="_selectDateForSearch"></DatePicker>
                </FormItem>
                <FormItem label="剧种">
                    <Select v-model="searchObj.perf_type.type_id" style="width:200px">
                        <Option v-for="(type, index) of types" :value="type.type_id" :key="index">{{ type.type_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="剧团">
                    <Select v-model="searchObj.perf_troupe.troupe_id" style="width:200px">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{ troupe.troupe_name }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="演出地点">
                    <Select v-model="searchObj.perf_addr.addr_id" style="width:200px">
                        <Option v-for="(addr, index) of addrs" :value="addr.addr_id" :key="index">{{ addr.addr_name }}</Option>
                    </Select>
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
                    <FormItem label="视频长度">
                        <Input type="text" v-model="addObj.perf_duration" number></Input>
                    </FormItem>
                    <FormItem label="视频大小">
                        <Input type="text" v-model="addObj.perf_size" number></Input>
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
                    <FormItem label="视频长度">
                        <Input type="text" v-model="editObj.perf_duration" number></Input>
                    </FormItem>
                    <FormItem label="视频大小">
                        <Input type="text" v-model="editObj.perf_size" number></Input>
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
        <Modal
            v-model="photoFlag"
            @on-ok="axiosPhoto"
        >
            <div class="demo-upload-list" v-for="(item, index) in uploadPhotoList" :key="index">
                <template>
                    <img :src="item.url">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline" @click.native="handleView(item.url)"></Icon>
                        <Icon type="ios-trash-outline" @click.native="photoRemove(item)"></Icon>
                    </div>
                </template>
            </div>

            <Upload
                    ref="upload"
                    multiple
                    type="drag"
                    :show-upload-list="false"
                    :action="photoAction"
                    :name="photoName"
                    :headers="photoHeaders"
                    :default-file-list="defaultPhotoList"
                    :format="photoFormat"
                    :max-size="photoSingleSize"
                    :on-format-error="photoFormatError"
                    :on-exceeded-size="photoSizeError"
                    :on-success="photoSuccess"
                    :on-error="photoError"
                    style="display: inline-block;width:58px;">
                <div style="width: 58px;height:58px;line-height: 58px;">
                    <Icon type="camera" size="20"></Icon>
                </div>
            </Upload>
        </Modal>

        <Modal title="View Image" v-model="visible">
            <img :src="visiblePath" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex'
    import objUtils from '@utils/objUtils'
    import httpUtils from '@utils/httpUtils'
    import storage from 'good-storage'
    import params from '@utils/params'

    var interval;
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
                //分页
                    count: 0,
                //新增
                    addFlag: false,
                    addAlterFlag: false,
                    addObj: this._resetAddObj(),
                //修改
                    editFlag: false,
                    editAlterFlag: false, //开关选填信息， 太占位置了
                    editObj: {},
                    editIndex: 0,
                //删除
                    deleteFlag: false,
                    perfId: "",
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
                //照片
                    visible:false,
                    visiblePath: "",
                    photoFlag: false,
                    photoList : [], //用于通讯
                    defaultPhotoList: [],
                    uploadPhotoList: [], //用于展示图片（与上传的photoList只含有id不同，内容较多），
                    photoHeaders:{
                      'Authorization': `Bearer ${storage.get('token')}`,
                      'type' : 'photo'
                    },
                    photoAction: `${params.baseUrl}/file/upload`,
                    photoName:'photo',  //给后端的字段名
                    photoFormat: params.photoFormat,
                    photoSingleSize: params.photoSingleSize,
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
            //todo mounted 一般跟dom有关初始化的在这个周期开始操作
            //调整表格大小
            this.resetTableWidth()
            //获取数据
            this.fetchData('page')
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
        computed:{
            ...mapGetters([
                'tableData'
            ])
        },
        methods:{
            //照片
            togglePhotoModal(row){
                this.photoFlag = !this.photoFlag
                this.editIndex = row._index//用于高亮
                //fixme 后台还没写
                this.defaultPhotoList = row.perf_files.map(photo=>{
                    return {
                        file_id: photo.file_id,
                        name: photo.file_name,
                        url: params.photoReplacePath(photo.file_path)
                    }
                })
                this.uploadPhotoList = this.defaultPhotoList.slice()
                //row id
                this.photoList = []
                this.photoList.push(row.perf_id)        //在首位
                //将default值也放入photoList
                this.photoList = this.photoList.concat(this.defaultPhotoList.map(photo=>{
                    return photo.file_id
                }))
            },
            //点击确定
            //fixme 后期如果量大，可以改成md5识别重复文件以免传 or 报重复
            axiosPhoto(){
                let body = {}
                body.perf_id = this.photoList.shift()
                body.photoList = this.photoList
                this.$http
                    .post(`${this.name}/upload`, body)
                    .then(res=>{
                        this.$Message.success(res.data.msg)
                        this.fetchData('page', this.editIndex)
                    })
            },
            photoSuccess(response,file,fileList){
                this.photoList.push(file.response.file_id)
                this.uploadPhotoList.push({
                    name: file.response.perf_name,
                    file_id: file.response.file_id,
                    url: params.photoReplacePath(file.response.file_path)
                })
            },
            photoRemove(file, fileList){
                this.photoList = this.photoList.filter(photo=>photo !== file.file_id)
                this.uploadPhotoList = this.uploadPhotoList.filter(photo=>photo.file_id !== file.file_id)
            },
            handleView(path){
                this.visible = true
                this.visiblePath = path
            },
            photoError(err,file,fileList){
                this.$Message.error({
                        content: '上传失败，未知错误',
                        duration: 3
                })
            },
            photoSizeError(file, fileList){
                this.$Message.error({
                    content: `文件过大，不要超过${params.photoSingleSize}KB!`,
                    duration: 3
                })
            },
            photoFormatError(file, fileList){
                    this.$Message.error({
                        content:`文件格式错误!只支持${params.photoFormat.join(",")}!`,
                        duration: 3
                    })
            },
            //检索
            toggleSearchModal(){
                this.searchFlag = !this.searchFlag
            },
            _selectDateForSearch(v){
                this.searchObj.perf_date = v
            },
            search(){
               let body = Object.assign({}, this.searchObj)
                     body = this._pakNormalBody(body)
                this.searchCondition = body
                this.fetchData('page')
            },
            //增加
            toggleAddModal(){
                this.addFlag = !this.addFlag
            },
            _toggleAddAlterFlag(){
              this.addAlterFlag = !this.addAlterFlag
            },
            _selectDateForAdd(v){
                this.addObj.perf_date = v
            },
            add(){
                let body = this._pakAddBody()
                this.$http
                    .post(`${this.name}/store`, body)
                    .then(res=>{
                        this.$Message.success(res.data.msg)
                        this.page = 1               //无论在哪一页新增，都会回到最初的那页
                        this.fetchData('page', 0)   //新增的总是在最上面出现
                    })
            },
            //组装add的通讯用的body
            _pakAddBody(){
                let body = objUtils.deepClone(this.addObj)
                    body = this._pakNormalBody()
                this.addObj = this._resetAddObj(body)
                return body
            },
            _pakNormalBody(body){
                Reflect.set(body, 'perf_type', body.perf_type.type_id)
                Reflect.set(body, 'perf_troupe', body.perf_troupe.troupe_id)
                Reflect.set(body, 'perf_addr', body.perf_addr.addr_id)
                return body
            },
            //addObj重置，复用在data定义和http通讯两个地方
            _resetAddObj(){
                return {
                    perf_troupe: {troupe_id:""},
                    perf_type:{type_id:""},
                    perf_addr:{addr_id:""},
                    perf_actors: []
                }
            },
            //修改
            toggleEditModal(source, index){
                    this.editIndex = index
                    this.editFlag = !this.editFlag
                    this.editObj = objUtils.deepClone(source)
                    this._initActorsValue()
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
            edit(){
                let body = this._pakEditBody()
                this.$http
                    .post(`${this.name}/update`, body)
                    .then(res=>{
                        this.$Message.success(res.data.msg)
                        this.fetchData('page', this.editIndex)
                    })
            },
            _pakEditBody(){
                let body = objUtils.deepClone(this.editObj),
                    deleteKeyArr = ['perf_detail', '_index', '_rowKey', 'actors']
                    Reflect.set(body, 'perf_actors', body.actors)
                    body = this._pakNormalBody(body)

                    deleteKeyArr.forEach(key => {
                        Reflect.deleteProperty(body, key)
                    })
                    return body
            },
            _selectDateForEdit(date){
                this.editObj.perf_date = date
            },
            //删除
            toggleDeleteModel(perf_id){
                this.deleteFlag = !this.deleteFlag
                this.perfId = perf_id
            },
            destroy(){
                this.$http
                    .get(`${this.name}/delete/${this.perfId}`)
                    .then(res=>{
                        this.$Message.success(res.data.msg)
                        this.fetchData('page')
                    })
            },
            //翻页
            pageTurning(page){
                this.page = page
                this.fetchData('page')
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
            resetTableWidth(){
                let curComponentWidth = document.querySelectorAll("div[class='ivu-layout-content']")[0].clientWidth
                if(curComponentWidth > 1400){
                    this.tableWidth = 1280
                }else{
                    this.tableWidth = curComponentWidth *0.8
                }
            },
            fetchData(api, highlight = null){
                let condition = {
                    page: this.page,
                    pageSize: this.pageSize,
                }
                if(this.searchCondition instanceof Object){
                    condition.searchCondition = this.searchCondition
                }
                this.loading = true
                this.$http
                    .post(`${this.name}/${api}`, condition)
                    .then(res=>{
                        this.loading = false
                        res = res.data
                        if(highlight || highlight === 0) res.data.data[highlight]._highlight = true;   //是否要高亮某一行 注意第0行不要被当成null...
                        this.$Message.success(res.msg)
                        this.setTableData(res.data.data)
                        this.count = res.data.count
                    })
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
            },
            ...mapMutations({
                setTableData:'SET_TABLE_DATA'
            })
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
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
.demo-upload-list
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
    img
        width: 100%;
        height: 100%;
    .demo-upload-list-cover
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    &:hover .demo-upload-list-cover
        display: block;
        i
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            margin: 0 2px;

</style>