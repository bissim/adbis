/**
 * Associate click events to page buttons
 * after page has been loaded
 */
$(document).ready(function () {
    // assign events to radio
    $("input[type = radio]#searchByTitle").click(swapSearch);
    $("input[type = radio]#searchByAuthor").click(swapSearch);
    $("button[type = submit]#sendMessageButton").click(searchBooks);
    $("form#contactForm").submit(function (event) {
        event.preventDefault(); // prevent page reload
    });

    // assign click event
    // to Search button
    // TODO activate
    // try {
    //     $('input[type = button]').click(search);
    // } catch (e) {
    //     console.error("An error occurred!\n" + e);
    // }
});

/**
 * Determine whether search must be
 * based on title or author
 */
function swapSearch() {
    let isAuthorRadio = $('#searchByAuthor').prop("checked");
    let isTitleRadio = $('#searchByTitle').prop("checked");

    if (isAuthorRadio) {
        // console.debug("Author selected");
        $("#keyword").prop("placeholder", "Autore");
    } else if (isTitleRadio) {
        // console.debug("Title selected");
        $("#keyword").prop("placeholder", "Titolo");
    } else {
        console.error("wat");
    }
}

/**
 * Base endpoint for entity search.
 * @type {string}
 */
let baseSearchUrl = '/adbis/search/';

/**
 * Generic function to search for books or reviews.
 */
function search() {
    // Si distingue il caso di ricerca per autore e ricerca per titolo
    let id = $(this).attr("id");

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
            console.error('Unknown button ID!');
            break;
    }

    // Si effettua la chiamata AJAX
    let searchUrl = baseSearchUrl + table;
    // console.debug("Sending request to " + searchUrl + "...");
    if (table === "book") {
        $.ajax({
            url : searchUrl,
            data : {
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
            url: searchUrl,
            data: {
                'search': search,
                'keyword': keyword
            },
            success: showReviews,
            error: ajaxError
        });
    }
}

/**
 * Specific function to search for books.
 */
function searchBooks() {
    let search = $("input[name = search]:checked, #sentMessage").val();
    let keyword = $("input#keyword").val();
    // console.debug("Searching for " + search.toString() + " " + keyword.toString() + "...");

    // AJAX call
    let searchUrl = baseSearchUrl + "book";
    // console.debug("GET " + searchUrl + "...");
    $.ajax({
        url: searchUrl,
        data: {
            'search': search,
            'keyword': keyword
        },
        // beforeSend: function (xhr) {},
        success: showBooks,
        error: ajaxError
    });
}

/**
 * Specific function to search for reviews.
 */
function searchReviews() {
    let search = $("input[name = search]:checked, #sentMessage").val();
    let keyword = $("input#keyword").val();

    // AJAX call
    let searchUrl = baseSearchUrl + "review";
    $.ajax({
        url: searchUrl,
        data: {
            'search': search,
            'keyword': keyword
        },
        success: showReviews,
        error: ajaxError
    });
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
    // console.debug("Object received: " + res);
    if (res) {
        let message =  "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
        $("#success").html(message);
    }

    let results = "";

    try {
        // create object from JSON
        let json = JSON.parse(res);

        // iterate over results array
        let resultNode = "";
        $.each(json, function (i, value) {
            // create result node
            // console.debug("Creating element " + i + "...");
            resultNode = "<div class='row'>";
            resultNode += "<img src='" + value.image + "' style='float:right;'/>";
            resultNode += "<a href='" + value.link + "'>";
            resultNode += "<strong>" + value.title + "</strong>";
            resultNode += "</a><br />";
            resultNode += "di&nbsp;" + value.author + "<br />";
            resultNode += "EUR&nbsp;" + value.price + "<br />";
            resultNode += "</div><hr />";

            results += resultNode;
        }); // TODO properly create a result node
    } catch (e) {
        throw e;
    }

    // populate results div
    console.debug("Populate results container...");
    $("#results").html(results);
    $("#resultsContainer").show();
}

/**
 * Show results for review query in page.
 * @param res
 */
function showReviews(res) {
    let txt = "";

    // console.debug("Object received: " + res);

    try {
        // create object from JSON
        let json = JSON.parse(res);

        // iterate over array
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
        throw e;
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