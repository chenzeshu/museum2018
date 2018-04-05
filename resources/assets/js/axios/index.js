import axios from 'axios'
import storage from 'good-storage'
import {app} from '../app'
import state from '../store/states'
import params from '@utils/params'
//todo 默认API前缀
axios.defaults.baseURL = params.baseUrl

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
    let token = response.headers.authorization.replace(/Bearer /, "")
    storage.set('token', token)
    return response
}, error => {
    let status = error.response.status,
        err = error.response.data

    //是不是用HTTP状态码更好？这样子感觉很乱
    switch (status){
        case 401:
            report(error, err, status)
            state.flagLogin = false
            break
        case 500:
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
        //token invalid
        app.$Message.error({
            content: `${status}: 登陆过期，请重新登陆，或不要过快刷新`,
            // content: `${status}: 请勿过快刷新${error.response.data.error}`,
            duration: 3
        })
    }
}


export default axios