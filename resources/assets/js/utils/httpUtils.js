const debounce = (f, delay) => {
    var timer

    return function (...args) {
        if(timer){
            clearTimeout(timer)
        }
        timer = setTimeout(function(){
            f.apply(this, args)
        }, delay)
    }
}

export default {
    debounce
}