<template>
    <div class="actor">
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
        <!--检索-->
        <Modal
                v-model="searchFlag"
                title="检索"
                @on-ok="search"
        >
            <Form ref="searchForm" :model="searchObj"  :label-width="80">
                <FormItem label="演员姓名">
                    <Input placeholder="模糊姓名" v-model="searchObj.actor_name" style="width:160px"></Input>
                </FormItem>
                <FormItem label="年龄范围">
                    <Input placeholder="默认0岁" v-model="searchObj.beginAge" style="width:80px;margin-right:16px" Number></Input>——
                    <Input placeholder="默认100岁" v-model="searchObj.endAge" style="width:80px;margin-left:16px" Number></Input>
                </FormItem>
                <FormItem label="演员性别">
                   <RadioGroup v-model="searchObj.actor_sex" type="button">
                       <Radio label="男"></Radio>
                       <Radio label="女"></Radio>
                   </RadioGroup>
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
                <FormItem label="演员姓名">
                    <Input v-model="addObj.actor_name" style="width:200px"></Input>
                </FormItem>
                <FormItem label="性别">
                    <RadioGroup type="button" v-model="addObj.actor_sex">
                        <Radio label="男"></Radio>
                        <Radio label="女"></Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem label="年龄">
                    <Input v-model="addObj.actor_age" placeholder="阿拉伯数字" Number style="width:200px"></Input>
                </FormItem>
                <FormItem label="所在剧团">
                    <Select v-model="addObj.actor_troupeNow.troupe_id" style="width:200px">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{troupe.troupe_name}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="获奖经历">
                    <Input v-model="addObj.actor_record" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="他/她的获奖经历..."></Input>
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
                <FormItem label="演员姓名">
                    <Input v-model="editObj.actor_name" style="width:200px"></Input>
                </FormItem>
                <FormItem label="性别">
                    <RadioGroup type="button" v-model="editObj.actor_sex">
                        <Radio label="男"></Radio>
                        <Radio label="女"></Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem label="年龄">
                    <Input v-model="editObj.actor_age" placeholder="阿拉伯数字" Number style="width:200px"></Input>
                </FormItem>
                <FormItem label="所在剧团">
                    <Select v-model="editObj.actor_troupeNow.troupe_id" style="width:200px">
                        <Option v-for="(troupe, index) of troupes" :value="troupe.troupe_id" :key="index">{{troupe.troupe_name}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="获奖经历">
                    <Input v-model="editObj.actor_record" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="他/她的获奖经历..."></Input>
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
        <!--图片-->
        <PhotoModal
                :photoFlag="photoFlag"
                :uploadPhotoList="defaultPhotoList"
                @on-confirm="onConfirm"
                @on-flag="onPhotoFlag"
        ></PhotoModal>
        <!--获奖经历-->
        <Modal
                v-model="recordFlag"
                title="获奖经历"
        >
            <p>{{this.record}}</p>
        </Modal>
        <!--历史剧团-->
        <Modal
                v-model="historyFlag"
                title="所在剧团历史（越上越新）"
        >
            <Card style="width:450px">
                <p slot="title">
                    当前：{{this.now.troupe_name || '未填写'}}
                </p>
                <span slot="extra" style="margin-right:16px">
                     {{this.now.history_time || ''}}
                </span>
                <ul>
                    <li v-for="(item, index) of histories" :key="index" style="margin:0 16px;padding:6px 0;">
                        <a href="#">{{ item.troupe_name }}</a>
                        <span style="float: right">{{ item.history_time }}</span>
                    </li>
                </ul>
            </Card>
        </Modal>
    </div>
</template>

<script>
    import cloneDeep from "lodash.clonedeep"
    import PhotoModal from '@base/PhotoModal/PhotoModal'
    import params from '@utils/params'
    import photoMixin from '@mixins/photoMixin.js'
    import dataAndPageMixin from '@mixins/dataAndPageMixin.js'
    import curdMixin from '@mixins/curdMixin.js'

    export default {
        mixins: [photoMixin, dataAndPageMixin, curdMixin],
        name: "actor",
        data(){
            return {
                name: "actor",
                historyFlag: false,
                now: {},    //当前的，不能在tempalte里直接histories.pop会无限循环
                histories: [],
                recordFlag: false,
                record: "",
                //检索
                searchFlag:false,
                searchObj:{
                    actor_sex: "男",
                    beginAge: 0,
                    endAge: 100
                },
                //工具信息baseData
                troupes: [],
                //修改
                editObj: {actor_troupeNow:{troupe_id:""}},
                columns: [
                    {
                        title: 'ID',
                        key: 'actor_id',
                        width: 80,
                        fixed: 'left'
                    },
                    {
                        title: '演员姓名',
                        key: 'actor_name',
                        width: 120,
                        fixed: 'left'
                    },
                    {
                        title: '性别',
                        key: 'actor_sex',
                        width: 90,
                    },
                    {
                        title: '年龄',
                        key: 'actor_age',
                        width: 90,
                    },
                    {
                        title: '获奖经历',
                        key: 'actor_record',
                        width: 160,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.toggleRecordModal(params.row.actor_record)
                                        }
                                    }
                                }, '查看获奖经历')]
                            )
                        }
                    },
                    {
                        title: '剧团历史',
                        width: 160,
                        render: (h, params)=>{
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.toggleHistoryModal(params.row.actor_troupe_histories)
                                        }
                                    }
                                }, '查看所在剧团历史')]
                            )
                        }
                    },
                    {
                        title: '操作',
                        width: 200,
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
                                        marginRight: '10px'
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
                                            this.toggleDeleteModel(params.row.actor_id)
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
            //表格重置宽度
            this.resetTableWidth()
            //获取数据
            this.fetchData('page')
            setTimeout(this._fetBaseData, 1000)
        },
        methods:{
            toggleHistoryModal(histories){
                this.historyFlag = !this.historyFlag
                this.histories = cloneDeep(histories).reverse()
                console.log(this.histories)
                this.now = this.histories.length > 0 ? this.histories.shift() : {}
            },
            toggleRecordModal(record){
                this.recordFlag = !this.recordFlag
                this.record = record
            },
            //打开图片框方法重写
            _createDefaultPhotoList(row){
                let list = row.actor_files.map( photo =>{
                    return {
                        file_id: photo.file_id,
                        name: photo.file_name,
                        url: params.photoReplacePath(photo.file_path)
                    }
                })
                return list
            },
            //将rowid刚入photoList
            _pushRowId(row){
                //重置 + 把row id放入首位
                this.photoList = new Array()
                this.photoList.push(row.actor_id)
            },
            //加入row的id
            shiftAxiosRowId(body){
                Reflect.set(body, 'actor_id',  this.photoList.shift())
                return body
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
                body.actor_age = [body.beginAge, body.endAge]
                this.searchCondition = body
                this.fetchData('page')
            },
            _resetAddObj(){
              return {
                  actor_troupeNow: {}
              }
            },
            _pakAddBody(){
                let body = Object.assign({}, this.addObj)
                Reflect.deleteProperty(body, 'actor_troupe_histroies')      //没有用的删掉
                return body
            },
            toggleEditModal(source, index) {
                this.editIndex = index
                this.editFlag = !this.editFlag
                this.editObj = cloneDeep(source)
                this.editObj.actor_troupeNow = source.actor_troupe_histories.slice(-1)[0] || {troupe_id:""}
            },
            _pakEditBody(){
                let body = cloneDeep(this.editObj)
                //看看新的剧团是否和剧团历史之前最新（最后面）的那个是同一个，是就证明没有修改过
                let lastOne = body.actor_troupe_histories.slice(-1)[0] || {troupe_id:""},
                     same = body.actor_troupeNow.troupe_id === lastOne.troupe_id

                Reflect.set(body, 'same', same)
                Reflect.deleteProperty(body, 'actor_troupe_histories')      //没有用的删掉
                Reflect.deleteProperty(body, 'actor_files')      //没有用的删掉
                return body
            },
            _fetBaseData(){
                this.$http
                    .get(`${this.name}/baseData`)
                    .then(res=>{
                        this.troupes = res.data.data.troupes
                    })
            }
        },
        components: {
            PhotoModal
        }

    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
    .actor
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