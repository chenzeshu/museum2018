function photoReplacePath(path) {
    return path.replace(/public/, "http://laravel.test/storage")
}

//剧种等只有一个name这类表
const smallWidth = {
    threshold: 980,        //开始自适应的阈值
    default: 980,       //表格默认宽度
    coefficient: 0.9       //表格宽度伸展系数
}

export default {
    baseUrl: 'http://laravel.test/api/v1',
    photoFormat: ['jpg','jpeg','png'],
    photoSingleSize: 500,
    tableWidth: {
        performance:{
            threshold: 1400,
            default: 1280,
            coefficient: 0.8
        },
        actor:{
            threshold: 1300,        //开始自适应的阈值
            default: 920,       //表格默认宽度
            coefficient: 0.72       //自适应时表格宽度伸展系数
        },
        troupe: smallWidth,
        type: smallWidth,
        baktype: smallWidth,
        addr: smallWidth,
        user: smallWidth
    },

    photoReplacePath,
}