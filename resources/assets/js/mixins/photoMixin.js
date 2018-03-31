import params from '@utils/params'

//tips: 配合PhotoModal.vue
export default {
    data(){
        return {
            //fixme 照片  可以再做个photoMixin配合组件， 引用两者就可以解决上传
            photoFlag: false,
            photoList : [], //用于通讯
            defaultPhotoList: [], //用于承载初始值
        }
    },
    methods:{
        //照片组件
        onConfirm(obj){
            let index = this.photoList.shift()      //避免误伤第一个row_id
            this.photoList = obj.photoList
            this.photoList.unshift(index)
            this.axiosPhoto()
        },
        onPhotoFlag(bool){
            this.photoFlag = bool
        },

        togglePhotoModal(row){
            this.photoFlag = !this.photoFlag
            this.editIndex = row._index//用于高亮
            this.defaultPhotoList = this._createDefaultPhotoList(row)
            this._pushRowId(row)
            //todo 将defaultList值也放入photoList
            this.photoList = this.photoList.concat(this.defaultPhotoList.map(photo=>{
                return photo.file_id
            }))
        },
        /**
         * 由于`row.perf_files`的存在， 在其他用到本mixin的组件里要重写这个方法
         * 当然，如果当初设计数据库时用的performance_files，或者直接前端的name就是缩写而不是`performance`，这个方法就也有办法复用了
         * 懒得搞字符串切割了。
         * @param row
         */
        //将files重新组装进defaultList
        _createDefaultPhotoList(row){
            let list = row.perf_files.map( photo =>{
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
            this.photoList.push(row.perf_id)
        },
        //点击确定
        //fixme 后期如果量大，可以改成md5识别重复文件以免传 or 报重复
        axiosPhoto(){
            let body = {}
            body = this.shiftAxiosRowId(body)
            body.photoList = this.photoList
            this.$http
                .post(`${this.name}/upload`, body)
                .then(res=>{
                    this.$Message.success(res.data.msg)
                    this.fetchData('page', this.editIndex)
                })
        },
        shiftAxiosRowId(body){
            Reflect.set(body, 'perf_id', this.photoList.shift())
            return body
        }
    }
}