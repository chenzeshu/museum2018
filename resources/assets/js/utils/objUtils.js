//深拷贝
const deepClone = function (target, source) {
    for(let key of Reflect.ownKeys(source)){
        if(key !== 'constructor' && key !== 'prototype' && key !== 'name'){ //不希望拷贝构造函数、显式原型及类名
            let desc = Object.getOwnPropertyDescriptor(source, key)  //从原对象上拿到函数明文
            Object.defineProperty(target, key, desc)  //像target上赋 key:desc
        }
    }
}

export default {
    deepClone
}