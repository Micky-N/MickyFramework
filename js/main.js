$(document).ready(function () {

    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.all();

});

function getCurrentPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('p');
    return page || 'home'
}

function template(view) {
    return 'views/' + view + '.html'
}

function mount(el) {
    let view = getCurrentPage()
    let file = template(view)
    $.get(file)
    .done(function(res) {
        if(!res.includes('<div id="app"></div>')){
            el.html(res)
        }
    }).fail(function() { 
        console.error(`template ${view}.html not found`)
    })
}


$(function () {
    let includes = $('[data-include]')
    $.each(includes, function () {
        let view = $(this).data('include')
        let file = template('includes/' + view)
        $(this).load(file)
    })
})

$(function () {
    let el = $('#app')
    mount(el)
})
