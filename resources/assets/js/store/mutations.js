import * as types from './mutation-types'

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
    }
}
