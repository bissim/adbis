// search page elements
let sendButton = $("button[type = submit]#sendMessageButton");
let searchField = $("input[type = text]#keyword");

// page name
let pageName;

$(document).ready(function () {
  determinePageName();

  switch (pageName) {
    case "":
      $("div#loadbox")
        .show()
        .children()
        .show();

      // async call to retrieve results
      $.ajax({
        url: baseSearchUrl + "news",
        beforeSend: prepareForResults,
        // success: showBoth
        success: showBooks
      });
      break;
    case "ebooks":
    case "audiobooks":
    default:
      $("input[type = radio]").click(changePH);

      // async call to retrieve results
      sendButton.click(searchBooks);

      // check search textfield
      searchField.keyup(disableSearchButton);
      searchField.focusout(disableSearchButton);

      // block form submission from reloading page
      $("form#contactForm").submit(function (event) {
        event.preventDefault(); // prevent page reload
      });
      break;
  }
});

/**
 * Base endpoint for entity search.
 * @type {string}
 */
let baseSearchUrl = "/adbis/search/";

/**
 * Determines which page
 * is currently browsed
 */
function determinePageName() {
  try {
    pageName = document.location.href.match(/[^\/]+$/)[0];
    // console.log("Page " + pageName);
  } catch (e) {
    // console.warn("I guess we're in main page here");
    pageName = "";
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
  } else {
    cancelButton.prop("disabled", true);
  }

  if (searchField.val().length > 2) {
    sendButton.prop("disabled", false);
  } else {
    sendButton.prop("disabled", true);
  }
}

/**
 * Change search field placeholder
 */
function changePH() {
  let id = $(this).attr('id');
  let word = "";
  switch (id) {
    case 'searchByAuthor': word = "Autore"; break;
    case 'searchByTitle': word = "Titolo"; break;
    case 'searchByVoice': word = "Doppiatore"; break;
    default: word = "";
  }
  searchField.attr("placeholder", word);
  $("#searchLabel").text(word);
}

/**
 * Generic function to search for books or reviews.
 */
function search() {
  // Si distingue il caso di ricerca per autore e ricerca per titolo
  let id = $(this).attr("id");

  let keyword, search, table;
  switch (id) {
    case "bookAutBtn":
      keyword = $("input[id = bookAut]").val();
      search = "author";
      table = "book";
      break;

    case "bookTitBtn":
      keyword = $("input[id = bookTit]").val();
      search = "title";
      table = "book";
      break;

    case "reviewAutBtn":
      keyword = $("input[id = reviewAut]").val();
      search = "author";
      table = "review";
      break;

    case "reviewTitBtn":
      keyword = $("input[id = reviewTit]").val();
      search = "title";
      table = "review";
      break;

    default:
      console.error("Unknown button ID!");
      break;
  }

  // Si effettua la chiamata AJAX
  let searchUrl = baseSearchUrl + table;
  // console.debug("Sending request to " + searchUrl + "...");
  if (table === "book") {
    $.ajax({
      url: searchUrl,
      data: {
        search: search,
        keyword: keyword
      },
      // Si stampano i risultati ottenuti
      success: showBooks,
      error: ajaxError
    });
  } else if (table === "review") {
    $.ajax({
      url: searchUrl,
      data: {
        search: search,
        keyword: keyword
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
  $("div#loadbox")
    .show()
    .children()
    .show();
  let search = $("input[name = search]:checked, #sentMessage").val();
  let keyword = searchField.val();
  let join = true;
  // console.debug(
  //   "Searching for " + search.toString() + " " + keyword.toString() + "..."
  // );
  // console.debug("Both? " + join);

  let endpoint = "";
  switch (pageName) {
    case "ebooks":
      endpoint = "book";
      break;
    case "audiobooks":
      endpoint = "audiobook";
      break;
  }

  // AJAX call
  let searchUrl = baseSearchUrl + endpoint;
  // console.debug("GET " + searchUrl + "...");
  $.ajax({
    url: searchUrl,
    data: {
      search: search,
      keyword: keyword,
      join: join
    },
    beforeSend: prepareForResults,
    success: showBoth,
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
      search: search,
      keyword: keyword
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
  resultsDiv.empty();
  $("#success").empty();

  // hide and delete former results
  if (!resultsDiv.hidden) {
    let message;
    switch (pageName) {
      case "":
        message = "lle nuove uscite";
        break;
      case "ebooks":
      case "audiobooks":
      default:
        message = "i risultati";
        break;
    }
    resultsDiv.hide();
    $("span#loadingMessage")
      .text(`Caricamento de${message}...`);
    // resultsDiv.append(loadingMessage);
    resultsDiv.show();
  }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBoth(res) {
  $("div#loadbox")
    .children()
    .hide();
  let loadingMessage = $("span#loadingMessage")
    .empty();
  $("div#resultsTitle").show();

  let json;

  try {
    json = JSON.parse(res);
  } catch (e) {
    let destination = "";
    switch (pageName) {
      case '':
        destination = "le ultime uscite";
        break;
      case 'ebooks':
      case 'audiobooks':
      default:
        destination = 'i risultati';
        break;
    }
    loadingMessage.html(
      `Impossibile recuperare ${destination}!`
    );
    loadingMessage.show();
    console.error(e.toLocaleString());
    return;
  }

  let message = "";
  let successMessage = $("#success");
  let numResults = Object.keys(json).length;
  if (numResults > 0) {
    message =
        "La ricerca ha ottenuto " + numResults + " risultati! " +
        "Consultare l'elenco sottostante.";
    message += "<br /><br />";
    if (successMessage) {
      successMessage.html(message);
    }
    let resultsDiv = $("#results");
    createResults(json, resultsDiv);
  } else {
    message = "La ricerca non ha prodotto alcun risultato!";
    message += "<br /><br />";
    if (successMessage) {
      successMessage.html(message);
    }
  }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
  // console.debug("hi I'll show books nao");
  let loadingMessage = $("span#loadingMessage");
  let json;
  let numResults;

  try {
    json = JSON.parse(res);
    numResults = Object.keys(json).length;
    console.debug("Object received: length " + numResults);
  } catch (e) {
    loadingMessage.html(
      "Impossibile recuperare i risultati!"
    );
    // loadingMessage.show();
    console.error(e.toLocaleString());
    console.debug(res);
    return;
  }

  let loadbox = $("div#loadbox")
    .children()
    .hide();

  let message = "";
  if (numResults > 0) {
    if (pageName.length > 0) {
      message =
        "La ricerca ha ottenuto " + numResults + " risultati! Consultare l'elenco sottostante.";
      $("#success").html(message);
    }

    try {
      // delete temporary message in results container
      let resultsDiv = $("#results")
        .empty()
        .append(
          "<div class='container'>" +
          "  <h1 style='margin-bottom: 40px;'>EBook pi&ugrave; recenti</h1>" +
          "</div>"
        );

      // show books
      createItemNodes(json['ebooks'], resultsDiv);
      loadingMessage.empty();

      // show audiobooks
      resultsDiv
        .append(
          "<div class='container'>" +
          "  <h1 style='margin-bottom: 40px;'>Audiobook pi&ugrave; recenti</h1>" +
          "</div>"
        );
      createItemNodes(json['aubooks'], resultsDiv);
    } catch (e) {
      console.error(e.toLocaleString());
    }
  } else {
    message = "Nessun nuovo prodotto da mostrare!";
    $("div#resLoad").hide();
    loadbox.show();
    loadingMessage
      .html(message)
      .show();
  }
}

/**
 *
 * @param json
 * @param resultsDiv
 */
function createItemNodes(json, resultsDiv) {
  $.each(json, function (i, item) {
    // create result node
    let resultNode = $("<div></div>")
      .attr("id", "resultNode" + (i + 1))
      .addClass("container")
      .css({marginBottom: "20px"})
      .hide();

    populateResultNode(i, item, resultNode);

    resultsDiv
      .append(resultNode)
      .append("<hr />");
    resultNode.fadeIn(800);
  });
}

/**
 *
 * @param json
 * @param resultsDiv
 */
function createResults(json, resultsDiv) {
  $.each(json, function (i, item) {
    let book = item[0];
    let review = item[1];

    // create result node
    let resultNode = $("<div></div>")
      .attr("id", "resultNode" + (i + 1))
      .addClass("container")
      .css({marginBottom: "20px"})
      .hide();

    populateResultNode(i, book, resultNode);

    // now append review to item node
    if (review != null) {
      let collapseNode = $("<span></span>");
      let chevron = $("<i></i>")
        .addClass("fas fa-chevron-down");
      let findMore = $("<a></a>")
        .css({fontSize: "14px"})
        .attr("data-toggle", "collapse")
        .attr("href", "#collapse" + (i + 1))
        .text("Scopri di più")
        .click(function () {
          chevron.toggleClass("fa-chevron-down fa-chevron-up");
        })
        .append("&nbsp;")
        .append(chevron);

      collapseNode
        .append(findMore);

      resultNode
        .children("div#details" + (i + 1))
        // .append("<br />")
        .append(collapseNode);

      let detailsContainerReview = $("<div></div>")
        .addClass("panel-collapse collapse")
        .attr("id", "collapse" + (i + 1))
        .css({clear: "both"});

      // create stats container
      let statsContainer = $("<div></div>")
        .attr("id", "stats" + (i + 1))
        .css({
          float: "left",
          width: "18%",
          margin: "6px 8px"
        })
        .append("<h4>Punteggi</h4>")
        .append(
            "<span>Voto: <strong>" + review.avg + "</strong></span><br />"
        )
        .append("<span>Stile: " + review.style + "</span><br />")
        .append("<span>Contenuto: " + review.content + "</span><br />")
        .append("<span>Piacevolezza: " + review.pleasantness + "</span>");

      detailsContainerReview.append(statsContainer);

      // create plot and review container
      let plotContainer = $("<div></div>")
        .append("<h4>Trama</h4>")
        .append("<p>" + review.plot + "</p>");
      let reviewTextContainer = $("<div></div>")
        .append("<h4>Recensione di un utente</h4>")
        .append("<p>" + review.text + "</p>");
      let textContainer = $("<div></div>")
        .attr("id", "text" + (i + 1))
        .css({
          float: "left",
          width: "78%",
          margin: "6px 8px",
          textAlign: "justify"
        })
        .append(plotContainer)
        .append(reviewTextContainer);

      detailsContainerReview.append(textContainer);
      resultNode.append(detailsContainerReview);
    }

    resultsDiv.append(resultNode);
    resultsDiv.append("<hr />");
    resultNode.fadeIn(800);
  });
}

/**
 *
 * @param i
 * @param item
 * @param resultNode
 */
function populateResultNode(i, item, resultNode) {
  let source = item["source"];
  let logoHeight = "18px";
  let src = "./view/img/";
  let alt = "";
  let title = "";
  let price = item["price"];
  price = price.toFixed(2);
  price += "&nbsp;&euro;";

  switch (source) {
    case 'amazon':
      src += "amazon_logo.png";
      alt = "Amazon";
      title = "Amazon";
      break;
    case 'audible':
      src += "audible_logo.png";
      alt = "Audible";
      title = "Audible";
      price = "Gratuito previo abbonamento";
      break;
    case 'google':
      src += "google_logo.png";
      alt = "Google";
      title = "Google";
      break;
    case 'ilnarratore':
      logoHeight = "26px";
      src += "ilnarratore_logo.png";
      alt = "IlNarratore";
      title = "IlNarratore";
      break;
    case 'kobo':
      src += "kobo_logo.png";
      alt = "Kobo";
      title = "Kobo";
      break;
    default:
      src += "unknown_logo.png";
      alt = "Unknown";
      title = "???";
      console.warn("Unknown source '" + source + "'!");
      break;
  }

  // create image container
  let imgContainerNode = $("<div></div>")
    .addClass("d-flex align-items-center")
    .css({
      float: "left",
      width: "200px",
      height: "200px",
      margin: "2px 20px 2px 20px"
    });
  let imgNode = $("<img src=\"\" />")
    .addClass("img-responsive")
    .attr("src", item["img"])
    .css({
      maxWidth: "180px",
      maxHeight: "180px",
      margin: "0 auto"
    });
  imgContainerNode.append(imgNode);
  resultNode.append(imgContainerNode);

  // create details container
  let itemAuthor = (item["author"] !== '')? item["author"]: "AA. VV.";
  let detailsContainerNode = $("<div></div>")
    .attr("id", "details" + (i + 1))
    .css({
      maxWidth: "800px",
      float: "left",
      padding: "2px 10px",
      overflowWrap: "break-word",
      wordWrap: "break-word",
      wordBreak: "break-word",
      hyphens: "auto"
    });
  // create book title
  let titleRow = $("<span></span>");
  let titleAnchor = $("<a></a>")
    .attr("href", item["link"])
    .append(
      $("<strong></strong>")
        .append(item["title"])
    );
  let logoNode = $("<img />")
    .addClass("img-responsive center-block")
    .attr("src", src)
    .attr("alt", alt)
    .attr("title", title)
    .css({
      maxHeight: logoHeight,
      margin: "4px 2px",
      padding: "2px"
    });

  titleRow
    .append(logoNode)
    .append("&nbsp;")
    .append(titleAnchor);

  detailsContainerNode
    .append(titleRow)
    .append("<br />")
    .append("<span>di&nbsp;<em>" + itemAuthor + "</em></span>")
    .append("<br />");
  if (pageName === 'audiobooks') {
    detailsContainerNode
      .append(
        "<span>letto da&nbsp;<em>" + item["voice"] + "</em></span>"
      )
      .append("<br />");
  }
  detailsContainerNode
    .append("<span>Prezzo:&nbsp;" + price + "</span>")
    .append("<br />");

  // detailsContainerNode.append(logoNode);
  resultNode.append(detailsContainerNode);
}

/**
 * Show results for review query in page.
 * @param res
 */
function showReviews(res) {
  // console.debug("Object received: " + res);

  // create object from JSON
  let json = JSON.parse(res);

  let loadingMessage = $("span#loadingMessage");
  loadingMessage.empty();

  if (Object.keys(json).length>0) {
    let message =
      "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
    $("#success").html(message);

    let resultsDiv = $("#results");

    try {
      let resultsDiv = $("#results");
      // delete temporary message in results container
      resultsDiv.empty();
  
      // show result
      createReviewNodes(json, resultsDiv);
    } catch (e) {
      throw e;
    }
  }
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
