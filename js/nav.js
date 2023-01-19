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
const trimSlashes = str => str.split('/').filter(v => v !== '').join('/');

function createMenu() {
    let res = ''
    menus.forEach(menu => {
        const {title, href} = menu
        const id = trimSlashes(href)
        const subMenus = menu.sub_menus || []

        res += '<li>'
        res += `<a href="${href}" id="${id || 'home'}">${title}`
        if(subMenus.length){
            res += '<span class="fas fa-caret-down"></span>'
        }
        res += '</a>'
        if(subMenus.length){
            res += `<ul class="item-show" data-show="${id}">`
            subMenus.forEach(subMenu => {
                res += `<li><a href="${subMenu.href}">${subMenu.title}</a></li>`
            })
            res += '</ul>'
        }
        res += '</li>'
    })
    return res
}

$('nav#sidebar ul#main_side').html(createMenu())

$('main#content').click(function () {
    $('.btn.nav_hamb.click').click()
})

function getCurrentPage() {
    const url = new URL(window.location.href);
    const trim = trimSlashes(url.pathname);
    return trim || 'home'
}

$('.btn.nav_hamb').click(function () {
    $(this).toggleClass("click");
    $('#sidebar').toggleClass("show");
});


$('nav#sidebar ul#main_side li a').each(function () {
    const id = $(this).attr('id');
    if(id && id === getCurrentPage()){
        $(this).parent().addClass("active");
        const currentLi = $(this).parent().find(`ul.item-show[data-show=${id}]`)
        if(currentLi){
            currentLi.addClass("show");
        }
        $('nav#sidebar ul#main_side').animate({
            scrollTop: $(this).parent().offset().top - 250
        }, 0);
    }
});