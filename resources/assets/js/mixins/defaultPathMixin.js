//用于刷新后首页地址router.push的保存
import storage from 'good-storage'
import {mapMutations} from 'vuex'

export default {
    methods: {
        setDefaultPathAndCatalog(){
            //为了刷新也有用
            storage.set('defaultPath', this.name)
            //正确显式目录
            this.chooseCatalog(this.name)
        },
        ...mapMutations({
            chooseCatalog: "CHOOSE_CATALOG"
        })
    }
}
