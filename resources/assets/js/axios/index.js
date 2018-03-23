import axios from 'axios'
import storage from 'good-storage'
import {app} from '../app'
import state from '../store/states'
//todo 默认API前缀
axios.defaults.baseURL = "http://laravel.test/api/v1"

//todo 请求拦截器
axios.interceptors.request.use(config=>{
    let token = storage.get('token')
    if(token){
        config.headers.Authorization = `Bearer ${token}`
    }else{
        //todo 跳转到登陆页面（通过设置flagLogin）
        state.flagLogin = false
    }
    return config
}, error => {
    return Promise.reject(error)
})

//todo 响应拦截器
axios.interceptors.response.use(response => {
    let token = response.headers.authorization
    storage.set('token', token)
    return response
}, error => {
    let status = error.response.status
    let err = error.response.data
    //是不是用HTTP状态码更好？这样子感觉很乱
    switch (status){
        case 401:
            report(error, err, status)
            state.flagLogin = false
            break
        case -10002:
            //密码错误
            app.$Message.error(err.msg)
            break
        default:
            console.log('未知错误，请联系管理员')
            break
    }
    return Promise.reject(error)
})

function report( error, err, status) {
    if(err.code && err.msg){
        app.$Message.error(`${err.code} : ${err.msg}`)
    }else{
        app.$Message.error(`${status}: ${error.response.data.error}`)
    }
}


export default axios