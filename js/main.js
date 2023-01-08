$(document).ready(function () {

    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.all();

});

function getCurrentPage() {
    const url = new URL(window.location.href);
    return url.searchParams.get('p') ?? 'home'
}

function changePage(page = 'home') {
    const url = new URL(window.location.href);
    let newUrl = url.origin
    window.location.href = newUrl + (page === 'home' ? '' : `?p=${page}`)
}

function template(view) {
    return 'views/' + view + '.html'
}

function mount(el) {
    let view = getCurrentPage()
    let file = template(view)
    $.each(el, function () {
        $(this).load(file)
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
