$(document).ready(function () {
    let searchUrl = '/adbis/search';

    // Si distingue il caso di ricerca per autore e ricerca per titolo
    $('input[type = button]').click(function () {
        let id;
        try {
            id = $(this).attr("id");
        } catch (e) {
            console.error("An error occurred!\n" + e);
        }

        let keyword, search, table;
        switch (id) {
            case 'bookAutBtn':
                keyword = $('input[id = bookAut]').val();
                search = 'author';
                table = 'book';
                break;

            case 'bookTitBtn':
                keyword = $('input[id = bookTit]').val();
                search = 'title';
                table = 'book';
                break;

            case 'reviewAutBtn':
                keyword = $('input[id = reviewAut]').val();
                search = 'author';
                table = 'review';
                break;

            case 'reviewTitBtn':
                keyword = $('input[id = reviewTit]').val();
                search = 'title';
                table = 'review';
                break;

            default:
                console.error('Unknown HTML ID!');
                break;
        }

        // Il json che si invia alla pagina php consiste nella parola chiave per la ricerca
        // e il tipo di ricerca (per autore o titolo)
        // obj = {"keyword": keyword, "search": search, "table": table};
        // dbParam = JSON.stringify(obj);

        // Si effettua la chiamata AJAX
        if (table === "book") {
            $.ajax({
                // url : "../controller/Mediator.php?x=" + dbParam,
                url : searchUrl,
                data : {
                    'table': table,
                    'search': search,
                    'keyword': keyword
                },
                // Si stampano i risultati ottenuti
                success : showBooks,
                error : ajaxError
            });
        }
        else if (table === "review") {
            $.ajax({
                url : searchUrl,
                data : {
                    'table': table,
                    'search': search,
                    'keyword': keyword
                },
                success : showReviews,
                error : ajaxError
            });
        }
    });
});

function showBooks(res) {
    let txt = "";
    let json = JSON.parse(res);

    console.debug("Object received: " + res);
    // return;

    try {
        $.each(json, function (i, value) {
            txt += "Titolo: " + value.title + "<br />";
            txt += "Autore: " + value.author + "<br />";
            txt += "Prezzo: " + value.price + "<br />";
            txt += "Immagine: " + value.image + "<br />";
            txt += "Link: " + value.link + "<hr />";
        });
    } catch (e) {
        console.error("An error occurred!\n" + e);
    }

    $("#demo").html(txt);
}

function showReviews(res) {
    let txt = "";
    let json = JSON.parse(res);

    console.debug("Object received: " + res);
    // return;

    try {
        $.each(json, function (i, value) {
            txt += "Titolo: " + value.title + "<br />";
            txt += "Autore: " + value.author + "<br />";
            txt += "Trama: " + value.plot + "<br />";
            txt += "Testo: " + value.txt + "<br />";
            txt += "Media: " + value.average + "<br />";
            txt += "Stile: " + value.style + "<br />";
            txt += "Contenuto: " + value.content + "<br />";
            txt += "Piacevolezza: " + value.pleasantness + "<hr />";
        });
    } catch (e) {
        console.error("An error occurred!\n" + e);
    }

    $("#demo").html(txt);
}

/**
 *
 * Manage AJAX errors
 *
 * @param request
 * @param status
 * @param error
 */
function ajaxError(request, status, error) {
    let $errorMessage = "An error occurred for request: " + request.toString();
    $errorMessage += ": " + error + " (status " + status + ").";

    console.error($errorMessage);
}