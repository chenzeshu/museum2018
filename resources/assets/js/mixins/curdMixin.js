
//tips: 引用这个mixin后，主component只需要建立几个pakObj函数 + iview的特殊组件事件
//tips2: toggleEditModal经常会特殊化，所以不引入了
export default {
    data(){
      return {
          //增加
          addFlag: false,
          addAlterFlag: false,          //开关选填信息标志，不管2个alterFlag能不能用到，都先建立了。
          addObj: this._resetAddObj(),  //addObj总是需要
          //修改
          editFlag: false,
          editAlterFlag: false,
          editObj: {},
          editIndex: 0,
          //删除
          deleteFlag: false,
          deleteId: "",
      }
    },
    methods:{
        /**  大概率需要重写的方法  **/
        _resetAddObj(){
          return {}
        },
        toggleEditModal(source, index) {
            this.editIndex = index
            this.editFlag = !this.editFlag
        },
        /***  基本不会被顶掉的  ***/
        toggleAddModal(){
            this.addFlag = !this.addFlag
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
        edit(){
            let body = this._pakEditBody()
            this.$http
                .post(`${this.name}/update`, body)
                .then(res=>{
                    this.$Message.success(res.data.msg)
                    this.fetchData('page', this.editIndex)
                })
        },
        //删除
        toggleDeleteModel(deleteId){
            this.deleteFlag = !this.deleteFlag
            this.deleteId = deleteId
        },
        destroy(){
            this.$http
                .get(`${this.name}/delete/${this.deleteId}`)
                .then(res=>{
                    this.$Message.success(res.data.msg)
                    this.fetchData('page')
                })
        },

    }
}