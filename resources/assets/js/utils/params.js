function photoReplacePath(path) {
    return path.replace(/public/, "http://laravel.test/storage")
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
            coefficient: 0.72       //表格宽度伸展系数
        }
    },

    photoReplacePath,
}