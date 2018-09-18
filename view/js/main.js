// search page elements
let sendButton = $("button[type = submit]#sendMessageButton");
let searchField = $("input[type = text]#keyword");

/**
 * Associate events
 * to loaded page
 */
$(document).ready(function () {
    // assign events to radio
    $("input[type = radio]#searchByTitle").click(swapSearch);
    $("input[type = radio]#searchByAuthor").click(swapSearch);
    // check search textfield
    searchField.keyup(disableSearchButton);
    searchField.focusout(disableSearchButton);
    // async call to retrieve results
    sendButton.click(searchBooks);
    // block form submission from reloading page
    $("form#contactForm").submit(function (event) {
        event.preventDefault(); // prevent page reload
    });
});

/**
 * Disable search button with
 * keywords shorter than 2
 */
function disableSearchButton() {
    let cancelButton = $("button[type = submit]#resetMessageButton");

    if (searchField.val().length > 0) {
        cancelButton.prop("disabled", false);
    }
    else {
        cancelButton.prop("disabled", true);
    }

    if (searchField.val().length > 2) {
        sendButton.prop("disabled", false);
    }
    else {
        sendButton.prop("disabled", true);
    }
}

/**
 * Determine whether search must be
 * based on title or author
 */
function swapSearch() {
    let isAuthorRadio = $('#searchByAuthor').prop("checked");
    let isTitleRadio = $('#searchByTitle').prop("checked");

    if (isAuthorRadio) {
        searchField.prop("placeholder", "Autore");
        $("#searchLabel").text("Autore");
    } else if (isTitleRadio) {
        searchField.prop("placeholder", "Titolo");
        $("#searchLabel").text("Titolo");
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
    let keyword = searchField.val();
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
        beforeSend: prepareForResults,
        success: showBooks,
        error: ajaxError
    });
}

/**
 * Specific function to search for reviews.
 */
function searchReviews() {
    let search = $("input[name = search]:checked, #sentMessage").val();
    let keyword = searchField.val();

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

function prepareForResults() {
    console.debug("Preparing for results...");

    let resultsContainerDiv = $("#resultsContainer");
    let resultsDiv = $("#results");

    // hide and delete former results
    if (!resultsContainerDiv.hidden) {
        resultsContainerDiv.hide();
        resultsDiv.html("<p>Caricamento dei risultati in corso...</p>");
        resultsContainerDiv.show();
    }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
    // console.debug("Object received: " + res);

    let resultsDiv = $("#results");

    if (res) {
        let message =  "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
        $("#success").html(message);
    }

    try {
        // delete temporary message in results container
        resultsDiv.empty();

        // create object from JSON
        let json = JSON.parse(res);

        // iterate over results array
        let resultNode = "";
        $.each(json, function (i, value) {
            // create result node
            resultNode = $("<div></div>").attr("id", "res" + i).attr("class", "row");
            resultNode.hide();

            // create image container
            let imgContainerNode = $("<div></div>")
                .attr("style", "float:right;width:200px;height:200px;margin:2px 4px 2px 4px;");
            let imgNode = $("<img />")
                .attr("class", "img-responsive center-block")
                .attr("src", value.image)
                .attr("style", "max-width:190px;max-height:190px;");
            imgContainerNode.append(imgNode);
            resultNode.append(imgContainerNode);

            // create details container
            let detailsContainerNode = $("<div></div>");
            detailsContainerNode
                .append("<span><a href='" + value.link + "'><span><strong>" + value.title + "</strong></span></a></span><br />")
                .append("<span>di&nbsp;<em>" + value.author + "</em></span><br />")
                .append("<span>Prezzo:&nbsp;" + value.price + "&euro;</span><br />");
            resultNode.append(detailsContainerNode);

            resultsDiv.append(resultNode);
            resultsDiv.append("<hr />");
            resultNode.fadeIn();
        }); // TODO properly create a result node
    } catch (e) {
        throw e;
    }
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