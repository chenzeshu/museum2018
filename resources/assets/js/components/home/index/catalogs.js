//左侧目录信息
export const catalogs = [
    {
        name: "1",
        iconType: "ios-navigate",
        title:"演出",
        menus: [
            {
                name: "1-1",
                title: "演出",
                icon: "videocamera",
                path: '/performance'
            },
        ]
    },
    {
        name: "2",
        iconType: "ios-navigate",
        title:"演员",
        menus: [
            {
                name: "2-1",
                title: "演员",
                icon: "man",
                path: '/actor'
            }
        ]
    },
    {
        name: "3",
        iconType: "ios-navigate",
        title:"其他",
        menus: [
            {
                name: "3-1",
                icon: "ios-people",
                title: "剧团",
                path: '/troupe'
            },
            {
                name:"3-2",
                icon: "ios-pricetag",
                title:"剧种",
                path: '/type'
            },
            {
                name:"3-3",
                icon: "location",
                title:"演出地点",
                path: '/addr'
            },
            {
                name:"3-4",
                icon: "android-settings",
                title:"备份类型",
                path: '/baktype'
            },
        ]
    },
    {
        name: "4",
        iconType: "ios-navigate",
        title:"平台",
        menus: [
            {
                name:"4-1",
                icon: "person",
                title:"用户表",
                path: '/user'
            },
        ]
    },
]