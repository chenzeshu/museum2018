function photoReplacePath(path) {
    return path.replace(/public/, "http://laravel.test/storage")
}


export default {
    baseUrl:'http://laravel.test/api/v1',
    photoFormat:['jpg','jpeg','png'],
    photoSingleSize: 500,
    photoReplacePath,
}