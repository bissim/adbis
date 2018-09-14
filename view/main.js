$(document).ready(function(){
    // Si distingue il caso di ricerca per autore e ricerca per titolo
    $('input[type=button]').click(function(){
        var id = $(this).attr("id");
        var keyword, search, table;
        switch (id) {
            case 'bookAutBtn': keyword = $('input[id=bookAut]').val();
                            search = 'author';
                            table = 'book';
            break;
            case 'bookTitBtn': keyword = $('input[id=bookTit]').val();
                            search = 'title';
                            table = 'book';
            break;
            case 'reviewAutBtn': keyword = $('input[id=reviewAut]').val();
                            search = 'author';
                            table = 'review';
            break;
            case 'reviewTitBtn': keyword = $('input[id=reviewTit]').val();
                            search = 'title';
                            table = 'review';
        }
        // Il json che si invia alla pagina php consiste nella parola chiave per la ricerca
        // e il tipo di ricerca (per autore o titolo)
        obj = { "keyword":keyword, "search":search, "table":table };
        dbParam = JSON.stringify(obj);
        // Si effettua la chiamata AJAX
        $.ajax({
            type : "GET",
            url : "../controller/Mediator.php?x=" + dbParam,
            dataType : "json",
            // Si stampano i risultati ottenuti
            success : function (res) {
                var txt = "";
                if (table == "book")
                {
                    $.each( res, function( i, value ) {
                    txt += "Titolo: " + value.title + "<br>" + 
                            "Autore: " + value.author + "<br>" +
                            "Prezzo: " + value.price + "<br>" + 
                            "Immagine: " + value.image + "<br>" +
                            "Link: " + value.link + "<hr>";
                });
            }
            else {
                $.each( res, function( i, value ) {
                    txt += "Titolo: " + value.title + "<br>" + 
                            "Autore: " + value.author + "<br>" +
                            "Trama: " + value.plot + "<br>" + 
                            "Testo: " + value.text + "<br>" +
                            "Media: " + value.avg + "<br>" +
                            "Stile: " + value.style + "<br>" +
                            "Contenuto: " + value.content + "<br>" +
                            "Piacevolezza: " + value.pleasantness + "<hr>";
                });                
            }
            $("#demo").html(txt);
            },
            error : function (richiesta, stato, errori) {
                console.error(
                    "An error occurred for request: " + richiesta.toString() +
                    ": " + errori + " (status " + stato + ")."
                );
            }
        });
    });
});