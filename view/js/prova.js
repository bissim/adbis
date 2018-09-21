// Ricava in formato JSON i nuovi ebook in vendita

$(document).ready(function () {
    $.ajax({
        url: '/adbis/search/news',
        success: function(res) {
            alert(res);
        }
    });
});