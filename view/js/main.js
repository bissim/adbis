// search page elements
let sendButton = $("button[type = submit]#sendMessageButton");
let deepButton = $("button#deepSearch");
let searchField = $("input[type = text]#keyword");

// page name
let pageName;

/**
 * Base endpoint for entity search.
 * @type {string}
 */
let baseSearchUrl = "/adbis/search/";

$(document).ready(function () {
    determinePageName();
    sessionStorage.setItem("deepSearch", "hide");

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
    case "audiobooks":
      $("input[type = radio]#searchByVoice").show();
      $("label#voiceLabel").show();
    case "ebooks":
    default:
      $("input[type = radio]").click(changePH);
      $("button#sendMessageButton").click(searchCache);
      deepButton.click(deepSearch);
      $("button#clear").click(clearResults);

      // async call to retrieve results
      // sendButton.click(searchBooks);
      // deepButton.click(searchBooks);

      // check search textfield
      searchField
        .keyup(disableSearchButton)
        .focusout(disableSearchButton);

      // block form submission from reloading page
      $("form#contactForm").submit(function (event) {
        event.preventDefault(); // prevent page reload
      });
      break;
  }
});

/**
 * Determines which page
 * is currently browsed
 */
function determinePageName() {
  try {
    pageName = document.location.href.match(/[^\/]+$/)[0];
    // console.log(`Page ${pageName}`);
  } catch (e) {
    // console.warn("I guess we're in main page here");
    pageName = "";
  }
}

/**
 * Enable search form
 */
function enableSearch() {
  searchField.prop("disabled", false);
  $("input[type = radio]#searchByTitle").prop("disabled", false);
  $("input[type = radio]#searchByAuthor").prop("disabled", false);
  $("input[type = radio]#searchByVoice").prop("disabled", false);
  sendButton.prop("disabled", false);
  $("button#clear").prop("disabled", false);
  deepButton.prop("disabled", false);
}

/**
 * Disable search form
 */
function disableSearch() {
  searchField.prop("disabled", true);
  $("input[type = radio]#searchByTitle").prop("disabled", true);
  $("input[type = radio]#searchByAuthor").prop("disabled", true);
  $("input[type = radio]#searchByVoice").prop("disabled", true);
  sendButton.prop("disabled", true);
  $("button#clear").prop("disabled", true);
  deepButton.prop("disabled", true);
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
    case 'searchByAuthor':
      word = "Autore";
      break;
    case 'searchByTitle':
      word = "Titolo";
      break;
    case 'searchByVoice':
      word = "Doppiatore";
      break;
    default:
      word = "";
      break;
  }

  searchField.attr("placeholder", word);
  $("#searchLabel").text(word);
}

function searchCache() {
  searchBooks('cache');
}

function deepSearch() {
  searchBooks('scraping');
}

/**
 * Specific function to search for books.
 */
function searchBooks(depth) {
  depth === 'cache'?
    sessionStorage.setItem('deepSearch', 'show'):
    sessionStorage.setItem('deepSearch', 'hide');
    
  $("div#resultsTitle").hide();
  $("div#loadbox")
    .show()
    .children()
    .show();
  let search = $("input[name = search]:checked, #sentMessage").val();
  let keyword = searchField.val();

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
  $.ajax({
    url: searchUrl,
    data: {
      search: search,
      keyword: keyword,
      depth: depth
    },
    beforeSend: prepareForResults,
    success: showBoth,
    error: ajaxError
  });
}

/**
 * Prepare results container
 */
function prepareForResults() {
  // console.debug("Preparing for results...");
  disableSearch();

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
  enableSearch();

  $("div#loadbox")
    .children()
    .hide();
  let loadingMessage = $("span#loadingMessage")
    .empty();

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
    // console.error(res);
    return;
  }

  let message = "";
  let successMessage = $("#success");
  let numResults = Object.keys(json).length;
  if (numResults > 0) {
    message =
        `La ricerca ha ottenuto ${numResults} risultati!
        Consultare l'elenco sottostante.`;
    message += `<br /><br />`;
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
    sessionStorage.getItem("deepSearch") === 'show'?
      deepButton.show():
      deepButton.hide();

}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
  let loadingMessage = $("span#loadingMessage");
  let json;
  let numResults;

  try {
    json = JSON.parse(res);
    numResults = Object.keys(json).length;
    // console.debug(`Object received: length ${numResults}`);
  } catch (e) {
    loadingMessage.html(
      "Impossibile recuperare i risultati!"
    );
    // loadingMessage.show();
    console.error(e.toLocaleString());
    // console.debug(res);
    return;
  }

  let loadbox = $("div#loadbox")
    .children()
    .hide();

  let message = "";
  if (numResults > 0) {
    if (pageName.length > 0) {
      message =
        `La ricerca ha ottenuto ${numResults} risultati!
        Consultare l'elenco sottostante.`;
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

    // create container for excessive search results
    showMore(resultsDiv, resultNode, item, i);
  });
}

/**
 *
 * @param json
 * @param resultsDiv
 */
function createResults(json, resultsDiv) {
  if (pageName !== "") {
    $("div#resultsTitle").show();
  }
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

    let chevron = $("<i></i>")
      .addClass("fas fa-chevron-down");

    // now append review to item node
    if (review != null) {
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

      let collapseNode = $("<span></span>")
        .append(findMore);

      resultNode
        .children("div#details" + (i + 1))
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
          `<span>Voto: <strong>${review.avg}</strong></span><br />`
        )
        .append(`<span>Stile: ${review.style}</span><br />`)
        .append(`<span>Contenuto: ${review.content}</span><br />`)
        .append(`<span>Piacevolezza: ${review.pleasantness}</span>`);

      detailsContainerReview.append(statsContainer);

      // create plot and review container
      let plotContainer = $("<div></div>")
        .append("<h4>Trama</h4>")
        .append(`<p>${review.plot}</p>`);
      let reviewTextContainer = $("<div></div>")
        .append("<h4>Recensione di un utente</h4>")
        .append(`<p>${review.text}</p>`);
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

    // create container for excessive search results
    showMore(resultsDiv, resultNode, book, i);
  });

  // enable clear button
  $("button#clear").prop("disabled", false);
}

/**
 *
 * @param resultsDiv
 * @param resultNode
 * @param book
 * @param i
 */
function showMore(resultsDiv, resultNode, book, i) {
  let firstChevron = $("<i></i>")
    .addClass("fas fa-chevron-down");
  let secondChevron = firstChevron.clone();

  let moreAnchor = pageName? pageName: book['voice']? "audiobooks": "books";

  let expandOtherResults = $("<span></span>")
    .css({
      display: "block",
      margin: "auto"
    })
    .append(
      $("<a></a>")
        .css({fontSize: "18px"})
        .attr("data-toggle", "collapse")
        .attr("href", `#more${moreAnchor}`)
        .append(firstChevron)
        .append("&nbsp;")
        .append("Altri risultati")
        .append("&nbsp;")
        .append(secondChevron)
        .click(function () {
          firstChevron.toggleClass("fa-chevron-down fa-chevron-up");
          secondChevron.toggleClass("fa-chevron-down fa-chevron-up");
        })
    )
    .hide();
  let otherResults = $("<div></div>")
    .attr("id", `more${moreAnchor}`)
    .addClass("row container panel-collapse collapse");

  resultsDiv
    .append(expandOtherResults)
    .append(otherResults);

  // determine where to append results
  if (i < 3) { // show results
    // console.log("Creating visible results...");
    resultsDiv
      .append(resultNode)
      .append("<hr />");
    resultNode.fadeIn(800);
  }
  else if (i === 3) {
    // create extended box results
    // console.log(`Appending result ${i} into extended box...`);
    // append first result
    otherResults
      .append(resultNode)
      .append("<hr />");
    expandOtherResults.show();
    resultNode.show();
  }
  else {
    // console.log(`Appending result ${i} into extended box...`);
    // append first result
    otherResults
      .append(resultNode)
      .append("<hr />");
    resultNode.show();
  }
}

/**
 *
 * @param i
 * @param item
 * @param resultNode
 */
function populateResultNode(i, item, resultNode) {
  let itemSource = item['source'];
  let logoHeight = "18px";
  let logoSrc = "./view/img/";
  let logoAlt = "";
  let logoTitle = "";
  let itemTitle = item['title'];
  let itemAuthor = (item['author'] !== '')? item['author']: "AA. VV.";
  let itemPrice = item['price'];

  // check item data
  if (itemTitle === "") {
    return;
  }

  // fix price representation
  itemPrice = itemPrice.toFixed(2);
  itemPrice += "&nbsp;&euro;";

  switch (itemSource) {
    case 'amazon':
      logoSrc += "amazon_logo.png";
      logoAlt = logoTitle = "Libro da Amazon";
      break;
    case 'audible':
      logoSrc += "audible_logo.png";
      logoAlt = logoTitle = "Audiolibro da Audible";
      itemPrice = "Gratuito previo abbonamento";
      break;
    case 'google':
      logoSrc += "google_logo.png";
      logoAlt = logoTitle = "Libro da Google";
      break;
    case 'ilnarratore':
      logoHeight = "26px";
      logoSrc += "ilnarratore_logo.png";
      logoAlt = logoTitle = "Audiolibro da IlNarratore";
      break;
    case 'kobo':
      logoSrc += "kobo_logo.png";
      logoAlt = logoTitle = "Libro da Kobo";
      break;
    default:
      logoSrc += "unknown_logo.png";
      logoAlt = "Libro da fonte non nota";
      logoTitle = "???";
      console.warn(`Unknown source '${itemSource}'!`);
      break;
  }

  // create image container
  let imgContainerNode = $("<div></div>")
    .addClass("d-flex align-items-center")
    .css({
      float: "left",
      width: "200px",
      height: "200px",
      margin: "2px 20px"
    });
  let imgNode = $("<img src=\"\" alt=\"\" />")
    .addClass("img-responsive")
    .attr("src", item["img"])
    .attr("alt", `Copertina di '${itemTitle}' di ${itemAuthor}`)
    .css({
      maxWidth: "180px",
      maxHeight: "180px",
      margin: "0 auto"
    });
  imgContainerNode.append(imgNode);
  resultNode.append(imgContainerNode);

  // create details container
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
    .attr("href", item['link'])
    .attr("rel", "noopener noreferrer")
    .attr("target", "_blank")
    .append(
      $("<strong></strong>")
        .append(itemTitle)
    );
  let logoNode = $("<img src=\"\" alt=\"\" />")
    .addClass("img-responsive center-block")
    .attr("src", logoSrc)
    .attr("alt", logoAlt)
    .attr("title", logoTitle)
    .css({
      maxHeight: logoHeight,
      margin: "0px 8px 4px 0px"
    });

  titleRow
    .append(logoNode)
    .append(titleAnchor);

  detailsContainerNode
    .append(titleRow)
    .append("<br />")
    .append(`<span>di&nbsp;<em>${itemAuthor}</em></span>`)
    .append("<br />");
  if (item["voice"]) {
    detailsContainerNode
      .append(
        `<span>letto da&nbsp;<em>${item["voice"]}</em></span>`
      )
      .append("<br />");
  }
  detailsContainerNode
    .append(`<span><strong>${itemPrice}</strong></span>`)
    .append("<br />");

    resultNode.append(detailsContainerNode);

}

/**
 * Clear search results
 */
function clearResults() {
  searchField.val("");
  $("div#resultsTitle").hide();
  $("div#results").empty();
  $("div#success").empty();
  $("button#clear").prop("disabled", true);
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
  let $errorMessage = `An error occurred for request: ${request.toString()}`;
  $errorMessage += `: ${error} (status ${status}).`;

  console.error($errorMessage);
}
