<template>
    <div class="photo-modal">
        <Modal
                v-model="flag"
                @on-ok="referFather"
                @on-cancel="cancel"
        >
            <div class="demo-upload-list" v-for="(item, index) in uploadPhotoListSlice" :key="index">
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
    import params from '@utils/params'
    import storage from 'good-storage'
    //fixme 等可行后，将外部uploadList清除掉，然后直接传defaultList进来
    export default {
        name: "photo-modal",
        data(){
            return {
                visible:false,
                visiblePath: "",
                flag:false,
                photoHeaders:{
                    'Authorization': `Bearer ${storage.get('token')}`,
                    'type' : 'photo'
                },
                photoAction: `${params.baseUrl}/file/upload`,
                photoName:'photo',  //给后端的字段名
                photoFormat: params.photoFormat,
                photoSingleSize: params.photoSingleSize,
                photoList : [],             //用于通讯
                uploadPhotoListSlice: []    //用于展示
            }
        },
        props: {
            photoFlag:{
                type: Boolean,
                default: false
            },
            uploadPhotoList:{
                type: Array,
                default: []
            }
        },
        created(){
            this.$watch('photoFlag', function(nFlag){
                this.flag = nFlag
            })
            this.$watch('uploadPhotoList', function(nList){
                this.uploadPhotoListSlice = nList.slice()
            })
            this.$watch('uploadPhotoListSlice', function (nSlice) {
                this.photoList = nSlice.map(n=>{
                    return n.file_id
                })
            })
        },
        destroyed(){
          console.log('photo模块被摧毁了')
        },
        methods:{
            cancel(){
              this.$emit('on-flag', false)
            },
            referFather(){
                    this.$emit('on-confirm', {photoList: this.photoList})
                    this.$emit('on-flag', false)
            },
            photoSuccess(response,file,fileList){
                // this.photoList.push(file.response.file_id)
                this.uploadPhotoListSlice.push({
                    name: file.response.perf_name,
                    file_id: file.response.file_id,
                    url: params.photoReplacePath(file.response.file_path)
                })
            },
            photoRemove(file, fileList){
                // this.photoList = this.photoList.filter(photo=>photo !== file.file_id)
                this.uploadPhotoListSlice = this.uploadPhotoListSlice.filter(photo=>photo.file_id !== file.file_id)
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
        }
    }
</script>

<style type="text/stylus" rel="stylesheet/stylus" lang="stylus">
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