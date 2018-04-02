import * as types from './mutation-types'
import {catalogs} from '../components/home/index/catalogs'

export default {
    /**
     * 改变登陆状态
     * @param states
     * @param {boolean} flag
     */
    [types.CHANGE_FLAG_LOGIN](state, flag){
        state.flagLogin = flag
    },
    [types.SET_TABLE_DATA](state, data){
        state.tableData = data
    },
    [types.CHOOSE_CATALOG](state, name){
        let check = catalogs.filter(catlog => {
            return catlog.menus.filter( menu => {
                return menu.path.replace(/^\//, "") === name
            }).length >= 1
        })

        if(check.length === 1){
            let menus = check[0].menus.filter(menu=>{
                return menu.path.replace(/^\//, "") === name
            })
            state.activeName = menus[0].name
            state.openNames = [check[0].name]
        }
    }
}
