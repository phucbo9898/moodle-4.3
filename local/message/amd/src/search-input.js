$(document).ready(function () {
    $('.submit-search').on('click', function(e){
        var url_current = window.location.origin + window.location.pathname;
        var keyword = $('.keyword-search').val()
        var type = $('.type-message').val()
        var url = url_current + '?keyword=' + keyword + '&typeMsg=' + type
        window.location.replace(url)
    });
})