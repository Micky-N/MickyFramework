const menus = [
    {
        title: 'Introduction',
        href: '?p=home'
    },
    {
        title: 'How to Install',
        href: '?p=install'
    },
    {
        title: 'Module',
        href: '?p=module',
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
    },
    {
        title: 'Routing',
        href: '?p=routing',
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
        href: '?p=providers'
    },
    {
        title: 'DI Container',
        href: '?p=container',
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
        href: '?p=database',
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
        href: '?p=orm',
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
        href: '?p=controller'
    },
    {
        title: 'View',
        href: '?p=view',
        sub_menus: [
            {
                title: 'Functions',
                href: '#functions'
            },
            {
                title: 'Filters',
                href: '#filters'
            },
            {
                title: 'Global variables',
                href: '#global_variables'
            },
            {
                title: 'Rendering',
                href: '#rendering'
            },
            {
                title: 'Twig Request',
                href: '#twig_request'
            },
            {
                title: 'Available functions',
                href: '#available_functions'
            }
        ]
    },
    {
        title: 'Request',
        href: '?p=request'
    },
    {
        title: 'Response',
        href: '?p=response'
    },
    {
        title: 'Filesystem',
        href: '?p=filesystem'
    },
    {
        title: 'Mail',
        href: '?p=mail'
    },
    {
        title: 'Log',
        href: '?p=log'
    },
    {
        title: 'Validator',
        href: '?p=validator'
    },
    {
        title: 'Cache',
        href: '?p=cache'
    },
    {
        title: 'Config',
        href: '?p=config'
    },
    {
        title: 'Cookies & Session',
        href: '?p=cookies_session'
    },
    {
        title: 'Middleware',
        href: '?p=middleware',
        sub_menus: [
            {
                title: 'Route Middleware',
                href: '#route_middleware'
            }
        ]
    },
    {
        title: 'Permission',
        href: '?p=permission'
    },
    {
        title: 'Event',
        href: '?p=event',
        sub_menus: [
            {
                title: 'Listener',
                href: '#listener'
            }
        ]
    },
    {
        title: 'MkyCommand CLI',
        href: '?p=command',
        sub_menus: [
            {
                title: 'List of commands',
                href: '#list'
            }
        ]
    }
]
const trimQuery = str => str.replace('?p=', '');

function createMenu() {
    let res = ''
    menus.forEach(menu => {
        const {title, href} = menu
        const id = trimQuery(href)
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
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('p');
    return page || 'home'
}

$('.btn.nav_hamb').click(function () {
    $(this).toggleClass("click");
    $('#sidebar').toggleClass("show");
});


$('nav#sidebar ul#main_side li a').each(function () {
    let id = $(this).attr('id');
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