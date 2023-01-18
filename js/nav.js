const menus = [
    {
        title: 'Introduction',
        href: '/'
    },
    {
        title: 'How to Install',
        href: '/install'
    },
    {
        title: 'Routing',
        href: '/routing',
        sub_menus: [
            {
                title: 'Controller',
                href: '#controller'
            },
            {
                title: 'File',
                href: '#file'
            },
        ]
    },
    {
        title: 'Providers',
        href: '/providers'
    },
    {
        title: 'DI Container',
        href: '/container',
        sub_menus: [
            {
                title: 'Container',
                href: '#container'
            },
            {
                title: 'Auto Wiring',
                href: '#auto_wiring'
            }
        ]
    },
    {
        title: 'Database',
        href: '/database',
        sub_menus: [
            {
                title: 'Migration',
                href: '#migration'
            },
            {
                title: 'Populator',
                href: '#populator'
            }
        ]
    },
    {
        title: 'ORM',
        href: '/orm',
        sub_menus: [
            {
                title: 'Manager',
                href: '#manager'
            },
            {
                title: 'QueryBuilder',
                href: '#query_builder'
            },
            {
                title: 'Entity',
                href: '#entity'
            },
            {
                title: 'Relationship',
                href: '#relationship'
            }
        ]
    },
    {
        title: 'Controller',
        href: '/controller'
    },
    {
        title: 'View',
        href: '/view'
    },
    {
        title: 'Request',
        href: '/request'
    },
    {
        title: 'Response',
        href: '/response'
    },
    {
        title: 'Filesystem',
        href: '/filesystem'
    },
    {
        title: 'Mail',
        href: '/mail'
    },
    {
        title: 'Log',
        href: '/log'
    },
    {
        title: 'Validator',
        href: '/validator'
    },
    {
        title: 'Cache',
        href: '/cache'
    },
    {
        title: 'Config',
        href: '/config'
    },
    {
        title: 'Cookies & Session',
        href: '/cookies_session'
    },
    {
        title: 'Middleware',
        href: '/middleware',
        sub_menus: [
            {
                title: 'Route Middleware',
                href: '#route_middleware'
            }
        ]
    },
    {
        title: 'Permission',
        href: '/permission'
    },
    {
        title: 'Event',
        href: '/event',
        sub_menus: [
            {
                title: 'Listener',
                href: '#listener'
            }
        ]
    },
    {
        title: 'MkyCommand CLI',
        href: '/command',
        sub_menus: [
            {
                title: 'List of commands',
                href: '#list'
            }
        ]
    },
    {
        title: 'Module',
        href: '/module',
        sub_menus: [
            {
                title: 'Folders organization',
                href: '#folders_organization'
            },
            {
                title: 'Configuration',
                href: '#configuration'
            },
            {
                title: 'HMVC Application',
                href: '#hmvc'
            }
        ]
    }
]

$('main#content').click(function(){
    $('.btn.nav_hamb.click').click()
})


$('.btn.nav_hamb').click(function () {
    $(this).toggleClass("click");
    $('.sidebar').toggleClass("show");
});


$('.sidebar ul li a').click(function () {
    const id = $(this).attr('id');
    $(`nav ul li ul.item-show[data-show!=${id}]`).removeClass("show");
    const currentLi = $(`nav ul li ul.item-show[data-show=${id}]`)
    if(!currentLi.hasClass("show")){
        currentLi.addClass("show");
    }else{
        currentLi.removeClass("show");
    }
    $('nav ul li #' + id + ' span').toggleClass("rotate");

});

$('nav ul li').click(function () {
    $(this).addClass("active").siblings().removeClass("active");
});