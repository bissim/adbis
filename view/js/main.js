// search page elements
let sendButton = $("button[type = submit]#sendMessageButton");
let searchField = $("input[type = text]#keyword");

/**
 * Global variable holding
 * current page name.
 */
// let pageName;

/**
 * Base endpoint for entity search.
 * @type {string}
 */
let baseSearchUrl = '/adbis/search/';

let numResult = 5;

/**
 * Associate events
 * to loaded page
 */
$(document).ready(function () {
    // set current page name
    let pageName = determinePageName();
    // console.debug("Hi u'r in " + pageName);

    // assign events to radio
    // $("input[type = radio]#searchByTitle").click(swapSearch);
    // $("input[type = radio]#searchByAuthor").click(swapSearch);
    // check search textfield
    searchField.keyup(disableSearchButton);
    searchField.focusout(disableSearchButton);
    // async call to retrieve results
    switch (pageName) {
        case '':
            // console.debug("hai dis is main page");
            $.ajax({
                url: baseSearchUrl + 'news',
                success: showBoth
            });
            break;
        case 'ebooks':
            sendButton.click(searchBooks);
            break;
        case 'reviews':
            sendButton.click(searchReviews);
            break;
        default:
            console.error("wait wat page is dis");
            break;
    }
    // block form submission from reloading page
    $("form#contactForm").submit(function (event) {
        event.preventDefault(); // prevent page reload
    });
});

/**
 * Determines which page
 * is currently browsed
 */
function determinePageName() {
    try {
        return document.location.href.match(/[^\/]+$/)[0];
    } catch (e) {
        // console.warn("I guess we're in main page here");
        return "";
    }
}

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

// function swapSearch() {
//     let isAuthorRadio = $('#searchByAuthor').prop("checked");
//     let isTitleRadio = $('#searchByTitle').prop("checked");
//     let join = $("#searchBoth");

//     if (isAuthorRadio) {
//         searchField.prop("placeholder", "Autore");
//         $("#searchLabel").text("Autore");
//         join.prop("disabled", true);
//         join.prop("checked", false);
//     } else if (isTitleRadio) {
//         searchField.prop("placeholder", "Titolo");
//         $("#searchLabel").text("Titolo");
//         join.prop("disabled", false);
//     } else {
//         console.error("wat");
//     }
// }

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
    let join = $("#searchBoth").prop("checked");
    console.debug("Searching for " + search.toString() + " " + keyword.toString() + "...");
    console.debug("Both? " + join);

    // AJAX call
    let searchUrl = baseSearchUrl + "book";
    // console.debug("GET " + searchUrl + "...");
    $.ajax({
        url: searchUrl,
        data: {
            'search': search,
            'keyword': keyword,
            'join': join
        },
        beforeSend: prepareForResults,
        success: join? showBoth: showBooks,
        error: ajaxError
    });
}

/**
 * Specific function to search for reviews.
 */
function searchReviews() {
    let search = $("input[name = search]:checked, #sentMessage").val();
    let keyword = searchField.val();
    // console.debug("Searching for " + search.toString() + " " + keyword.toString() + "...");

    // AJAX call
    let searchUrl = baseSearchUrl + "review";
    // console.debug("GET " + searchUrl + "...");
    $.ajax({
        url: searchUrl,
        data: {
            'search': search,
            'keyword': keyword
        },
        beforeSend: prepareForResults,
        success: showReviews,
        error: ajaxError
    });
}

/**
 * Prepare results container
 */
function prepareForResults() {
    // console.debug("Preparing for results...");

    // let resultsContainerDiv = $("#resultsContainer");
    let resultsDiv = $("#results");

    // hide and delete former results
    if (!resultsDiv.hidden) {
        resultsDiv.hide();
        resultsDiv.html("<p>Caricamento dei risultati in corso...</p>");
        resultsDiv.show();
    }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
    console.debug("hi I'll show books nao");
    // console.debug("Object received: " + res);

    if (res) {
        let message =  "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
        $("#success").html(message);
    }

    let resultsDiv = $("#results");

    try {
        // delete temporary message in results container
        resultsDiv.empty();

        // create object from JSON
        let json = JSON.parse(res);

        // show results
        createBookNodes(json, resultsDiv, numResult);
    } catch (e) {
        throw e;
    }
}

/**
 * Show results for review query in page.
 * @param res
 */
function showReviews(res) {
    // console.debug("Object received: " + res);

    if (res) {
        let message =  "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
        $("#success").html(message);
    }

    let resultsDiv = $("#results");

    try {
        // delete temporary message in results container
        resultsDiv.empty();

        // create object from JSON
        let json = JSON.parse(res);

        // show result
        createReviewNodes(json, resultsDiv, numResult);
    } catch (e) {
        throw e;
    }
}

/**
 *
 * @param res
 */
function showBoth(res) {
    // console.warn("implement me pls ___;-;");
    let json = JSON.parse(res);

    if (res) {
        let message =  "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
        let successMessage = $("#success");
        if (successMessage) {
            successMessage.html(message);
        }
    }

    let resultsDiv = $("#results");

    $.each(json, function (i, item)
    {
        book = item[0];
        review = item[1];
        let resultNode = "";

        // create result node
        resultNode = $("<div></div>")
            .attr("class", "row");
        resultNode.hide();

        // create image container
        let imgContainerNode = $("<div></div>")
            .attr("style", "float:right;width:200px;height:200px;margin:2px 4px 2px 4px;");
        let imgNode = $("<img />")
            .attr("class", "img-responsive center-block")
            .attr("src", book['image'])
            .attr("style", "max-width:190px;max-height:190px;");
        imgContainerNode.append(imgNode);
        resultNode.append(imgContainerNode);

        // create details container
        let detailsContainerBook = $("<div></div>");
        detailsContainerBook
            .append("<span><a href='" + book['link'] + "'><span><strong>" + book['title'] + "</strong></span></a></span><br />")
            .append("<span>di&nbsp;<em>" + book['author'] + "</em></span><br />")
            .append("<span>Prezzo:&nbsp;" + book['price'] + "&euro;</span><br />");
            resultNode.append(detailsContainerBook);

        if (review != null)
        {
            let detailsContainerReview = $("<div></div>");
            // create stats container
        let statsContainer = $("<div></div>")
            .attr("id", "stats" + i)
            .css({
                "float": "left",
                "width": "18%",
                "margin": "6px 8px"
            })
            .append("<h4>Punteggi</h4>")
            .append("<span>Voto: <strong>" + review.average + "</strong></span><br />")
            .append("<span>Stile: " + review.style + "</span><br />")
            .append("<span>Contenuto: " + review.content + "</span><br />")
            .append("<span>Piacevolezza: " + review.pleasantness + "</span>");
        detailsContainerReview.append(statsContainer);
        

        // create plot and review container
        let textContainer = $("<div></div>")
            .attr("id", "text" + i)
            .css({
                "float": "right",
                "width": "78%",
                "margin": "6px 8px"
            })
            .append("<div><h4>Trama</h4><p>" + review.plot + "</p></div>")
            .append("<div><h4>Recensione di un utente</h4><p>" + review.txt + "</p></div>");
        detailsContainerReview.append(textContainer);
        resultNode.append(detailsContainerReview);
        }


        resultsDiv.append(resultNode);
        resultsDiv.append("<hr />");
        resultNode.fadeIn();

        // if (0 !== numResults && numResults - 1 === i) {
        //     return false;
        // }
                
    });
}

function createBookWithReviewNode(book, review, resultsDiv, numResults) {
    // iterate over results array
    let resultNode = "";

        // create result node
        resultNode = $("<div></div>")
            .attr("class", "row");
        resultNode.hide();

        // create image container
        let imgContainerNode = $("<div></div>")
            .attr("style", "float:right;width:200px;height:200px;margin:2px 4px 2px 4px;");
        let imgNode = $("<img />")
            .attr("class", "img-responsive center-block")
            .attr("src", book['image'])
            .attr("style", "max-width:190px;max-height:190px;");
        imgContainerNode.append(imgNode);
        resultNode.append(imgContainerNode);

        // create details container
        let detailsContainerBook = $("<div></div>");
        detailsContainerBook
            .append("<span><a href='" + book['link'] + "'><span><strong>" + book['title'] + "</strong></span></a></span><br />")
            .append("<span>di&nbsp;<em>" + book['author'] + "</em></span><br />")
            .append("<span>Prezzo:&nbsp;" + book['price'] + "&euro;</span><br />");
            resultNode.append(detailsContainerBook);

        let detailsContainerReview = $("<div></div>");

        // create stats container
        let statsContainer = $("<div></div>")
            .attr("id", "stats" + i)
            .css({
                "float": "left",
                "width": "18%",
                "margin": "6px 8px"
            })
            .append("<h4>Punteggi</h4>")
            .append("<span>Voto: <strong>" + review.average + "</strong></span><br />")
            .append("<span>Stile: " + review.style + "</span><br />")
            .append("<span>Contenuto: " + review.content + "</span><br />")
            .append("<span>Piacevolezza: " + review.pleasantness + "</span>");
        detailsContainerReview.append(statsContainer);

        // create plot and review container
        let textContainer = $("<div></div>")
            .attr("id", "text" + i)
            .css({
                "float": "right",
                "width": "78%",
                "margin": "6px 8px"
            })
            .append("<div><h4>Trama</h4><p>" + review.plot + "</p></div>")
            .append("<div><h4>Recensione di un utente</h4><p>" + review.txt + "</p></div>");
        detailsContainerReview.append(textContainer);
        
        resultNode.append(detailsContainerReview);

        resultsDiv.append(resultNode);
        resultsDiv.append("<hr />");
        resultNode.fadeIn();

        if (0 !== numResults && numResults - 1 === i) {
            return false;
        }
}

/**
 *
 * @param json
 * @param resultsDiv
 * @param numResults
 */
function createBookNodes(json, resultsDiv, numResults) {
    // iterate over results array
    let resultNode = "";
    $.each(json, function (i, value) {
        // create result node
        resultNode = $("<div></div>")
            .attr("id", "res" + i)
            .attr("class", "row");
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

        if (0 !== numResults && numResults - 1 === i) {
            return false;
        }
    });
}

/**
 *
 * @param json
 * @param resultsDiv
 * @param numResults
 */
function createReviewNodes(json, resultsDiv, numResults) {
    // iterate over results array
    let resultNode = "";
    $.each(json, function (i, value) {
        // create results node
        resultNode = $("<div></div>")
            .attr("id", "res" + i)
            .attr("class", "row")
            .hide();

        // create title and author container
        let titleContainer = $("<div></div>")
            .attr("id", "title" + i)
            .css({
                "padding": "6px 8px",
                "margin": "6px 8px"
            })
            .append("<h3>" + value.title + "</h3><br />")
            .append("<span style=\"margin-left:8px;\">di <em>" + value.author + "</em></span><br />");
        resultNode.append(titleContainer);

        // create inner container
        let innerContainer = $("<div></div>")
            .attr("id", "inner" + i)
            .css("padding", "6px 8px");

        // create stats container
        let statsContainer = $("<div></div>")
            .attr("id", "stats" + i)
            .css({
                "float": "left",
                "width": "18%",
                "margin": "6px 8px"
            })
            .append("<h4>Punteggi</h4>")
            .append("<span>Voto: <strong>" + value.average + "</strong></span><br />")
            .append("<span>Stile: " + value.style + "</span><br />")
            .append("<span>Contenuto: " + value.content + "</span><br />")
            .append("<span>Piacevolezza: " + value.pleasantness + "</span>");
        innerContainer.append(statsContainer);

        // create plot and review container
        let textContainer = $("<div></div>")
            .attr("id", "text" + i)
            .css({
                "float": "right",
                "width": "78%",
                "margin": "6px 8px"
            })
            .append("<div><h4>Trama</h4><p>" + value.plot + "</p></div>")
            .append("<div><h4>Recensione di un utente</h4><p>" + value.txt + "</p></div>");
        innerContainer.append(textContainer);

        resultNode.append(innerContainer);
        resultsDiv
            .append(resultNode)
            .append("<hr />");
        resultNode.fadeIn();

        if (0 !== numResults && numResults - 1 === i) {
            return false;
        }
    });
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