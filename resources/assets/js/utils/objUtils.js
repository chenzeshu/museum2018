//深拷贝
const deepClone = function (oldValue) {
    let strValue = JSON.stringify(oldValue)
    return JSON.parse(strValue)
}

export default {
    deepClone
}