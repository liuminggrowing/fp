var rootDir = '/public/faceplay/publish/';

fis.match('*.{png,jpg,gif}', {
    url: rootDir + '$0'
});
fis.match('**.{eot,svg,ttf,woff}', {
    url: rootDir + '$0'
});
fis.match('*.{js,css,less}', {
    useHash: true,
    url: rootDir + '$0',
});
// 启用 fis-spriter-csssprites 插件
fis.match('::package', {
  spriter: fis.plugin('csssprites')
})
fis.match('*.less', {
    useHash: true,
    useSprite: true,
    parser: fis.plugin('less'),
    rExt: '.css',
    optimizer: fis.plugin('clean-css')
});
// fis.hook('commonjs');
// fis.match('widget/**.js', {
//   isMod: true
// });
//配置打包插件
fis.match('::package', {
    packager: fis.plugin('map', {
        '/js/common.js': [
            '/js/zepto.min.js',
            '/js/template.js',
        ],
        '/css/common.css': [
            '/css/common.less',
        ]
    }),
    spriter: fis.plugin('csssprites')
});
fis.match('festival.html',{
    rExt: '.php'
});
//部署控制
fis.match('**', {
    deploy: [
        //多语言编译模板
        fis.plugin('i18n-template', {
            open: '<%',
            close: '%>',
            i18n: 'lang',
            dist: '$lang/$file'  //目的地址: 其中$lang为语言,$file为文件名
        }), //过滤文件
        fis.plugin('filter', {exclude: ['/tpl/**', '/publish/**', '/lang/**', 'readme.txt']}),
        //本地发布
        // fis.plugin('local-deliver', {to: '../publish/'}),
        fis.plugin('http-push', {
            receiver: 'http://cp01-rdqa04-dev103.cp01.baidu.com:8187/receiver.php',
            to: '/home/users/gaochangcheng/h5/public/faceplay/publish/' // 注意这个是指的是测试机器的路径，而非本地机器
        }),
    ]
});

/*********************线上专属流程 begin*****************************/

//css压缩
fis.media('online').match('*.{css,less}', {
    optimizer: fis.plugin('clean-css')
});

//js文件混淆
fis.media('online').match('*.js', {
    optimizer: fis.plugin('uglify-js')
});

//png图片压缩
fis.media('online').match('*.png', {
    // fis-optimizer-png-compressor 插件进行压缩，已内置
    optimizer: fis.plugin('png-compressor',{
        type : 'pngquant'
    })
});

//发布图片
fis.media('online').match('*.{png,jpg,gif}', {
    release: '$0',
    url: rootDir + '$0',
    useDomain: true,
    domain: 'http://img.phone.baidu.com/public/uploads/fe'
});

//部署控制
fis.media('online').match('**', {
    //多语言编译模板
    deploy: [
        fis.plugin('i18n-template', {
            open: '<%',
            close: '%>',
            i18n: 'lang',
            dist: '$lang/$file',  //目的地址: 其中$lang为语言,$file为文件名
        }),
        //过滤文件
        fis.plugin('filter', { exclude: ['/tpl/**','/publish/**','readme.txt']}),
        //上传到cdn
        fis.plugin('cdn-httpupload',{
            cdnUrl:'http://hkg02-appmarket-static00.hkg02.baidu.com:8123/',
            basePath:  rootDir,
            fileType: ['.png','.jpg','.gif'],
        }),
        //本地发布
        fis.plugin('local-deliver',{to: './publish/'})
        // fis.plugin('http-push', {
        //     receiver: 'http://cp01-rdqa-dev420.cp01.baidu.com:8083/receiver.php',
        //     to: '/home/users/fangsimin/pc-web/h5/public/video_contest/publish/'
        // })
    ]
});

/*********************线上专属流程 end**************************/
