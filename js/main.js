$(document).ready(function () {

    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.all();

});

function getCurrentPage() {
    const url = new URL(window.location.href);
    return url.searchParams.get('p')
}

function changePage(page = 'home') {
    const url = new URL(window.location.href);
    let newUrl = url.origin
    window.location.href = newUrl + `?p=${page}`
}


var app = new Vue({
    el: '#app',
})
