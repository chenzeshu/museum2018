import {mapGetters, mapMutations} from 'vuex'

//tips: 分页一般和主fetData在一起，所以写一起了
//tipd2: 引用这个mixin后，主component只需要造几个延迟获取工具数据函数即可
export default {
    data(){
        return {
            //分页
            count: 0,
            page: 1,
            pageSize: 10,
            loading: false,     //主fetchDataLoding标志
        }
    },
    computed:{
        ...mapGetters([
            'tableData'
        ])
    },
    methods:{
        //翻页+电梯
        pageTurning(page){
            this.page = page
            this.fetchData('page')
        },
        fetchData(api, highlight = null){
            let condition = {
                page: this.page,
                pageSize: this.pageSize,
            }
            //fixme 这里其实还可以增加一个细节：重置sc，以使页面回到无条件的时候
            if(this.searchCondition instanceof Object){     //好像没引用searchMixin也没有影响
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
        ...mapMutations({
            setTableData:'SET_TABLE_DATA'
        })
    }
}