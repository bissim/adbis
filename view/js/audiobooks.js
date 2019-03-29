// search page elements
let sendButton = $("button[type = submit]#sendMessageButton");
let searchField = $("input[type = text]#keyword");
$("input[type = radio]").click(changePH);

/**
 * Global variable holding
 * current page name.
 */
// let pageName;

/**
 * Base endpoint for entity search.
 * @type {string}
 */
let baseSearchUrl = "/adbis/search/";

/**
 * Associate events
 * to loaded page
 */
$(document).ready(function() {
  // set current page name
  let pageName = determinePageName();
  // console.debug("Hi u'r in " + pageName);

  // async call to retrieve results
  sendButton.click(searchBooks);

  // block form submission from reloading page
  $("form#contactForm").submit(function(event) {
    event.preventDefault(); // prevent page reload
  });
});

/**
 * Change search field placeholder
 */
function changePH() {
  let id = $(this).attr('id');
  let word = "";
  switch(id) {
    case 'searchByAuthor': word = "Autore"; break;
    case 'searchByTitle': word = "Titolo"; break;
    case 'searchByVoice': word = "Doppiatore"; break;
    default: word = "";
  }
  searchField.attr("placeholder", word);
  $("#searchLabel").text(word);
}

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
 * Specific function to search for books.
 */
function searchBooks() {
  let search = $("input[name = search]:checked, #sentMessage").val();
  let keyword = searchField.val();
  let join = true;
  console.debug(
    "Searching for " + search.toString() + " " + keyword.toString() + "..."
  );
  console.debug("Both? " + join);

  // AJAX call
  let searchUrl = baseSearchUrl + "audioBook";
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
    resultsDiv.hide();
    // let loadingMessage = $("<p>Caricamento dei risultati in corso...</p>").attr(
    //   "id",
    //   "loadingMessage"
    // );
    // resultsDiv.append(loadingMessage);
    // $("div#resultsTitle").show();
    $("div#resLoad").show();
    resultsDiv.show();
  }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBoth(res) {
  // console.warn("implement me pls ___;-;");

  let loadingMessage = $("p#loadingMessage");
  loadingMessage.remove();
  $("div#resLoad").hide();

  let json = JSON.parse(res);

  let message = "";
  let successMessage = $("#success");
  let numResults = Object.keys(json).length;
  if (numResults > 0) {
    message =
      "La ricerca ha ottenuto " + numResults + " risultati! Consultare l'elenco sottostante.";
    message += "<br /><br />";
    if (successMessage) {
      successMessage.html(message);
    }
    let resultsDiv = $("#results");
    createBookWithReviewNode(json, resultsDiv);  
  } else {
    message = "La ricerca non ha prodotto alcun risultato!";
    message += "<br /><br />";
    if (successMessage) {
      successMessage.html(message);
    }
  }
}

/**
 *
 * @param json
 * @param resultsDiv
 */
function createBookWithReviewNode(json, resultsDiv) {
  $.each(json, function(i, item) {
    let book = item[0];
    let review = item[1];
    let resultNode = "";

    let source = book["source"];
    let dim = "";
    let src = "./view/img/";
    let alt = "";
    let title = "";
    let price = "";

    switch (source) {
      case 'audible':
        dim = "30px";
        src += "audible_logo.png";
        alt = "Audible";
        title = "Audible";
        price = "Gratuito previo abbonamento";
        break;
      case 'ilnarratore':
        dim = "50px";
        src += "ilnarratore_logo.png";
        alt = "IlNarratore";
        title = "IlNarratore";
        price = book["price"]+"&euro;";
        break;
      default:
        console.warn("Unknown source '" + source + "'!");
        break;
      }

    // create result node
    resultNode = $("<div></div>")
      .attr("id", "resultNode" + (i + 1))
      .attr("class", "container")
      .attr("style", "margin-bottom:50px");
    resultNode.hide();

    // create image container
    let imgContainerNode = $("<div></div>").attr(
      "style",
      "float:left;width:200px;height:200px;margin:2px 30px 2px 30px;"
    );
    let imgNode = $("<img />")
      .attr("class", "img-responsive center-block")
      .attr("src", book["img"])
      .attr("style", "max-width:190px;max-height:190px;");
    imgContainerNode.append(imgNode);
    resultNode.append(imgContainerNode);

    // create details container
    let bookAuthor = (book["author"] !== '')? book["author"]: "AA. VV.";
    let detailsContainerBook = $("<div></div>");
    detailsContainerBook
      .append(
        "<span><a href='" +
          book["link"] +
          "'><span><strong>" +
          book["title"] +
          "</strong></span></a></span><br />"
      )
      .append("<span>di&nbsp;<em>" + bookAuthor + "</em></span><br />")
      .append("<span>letto da&nbsp;<em>" + book["voice"] + "</em></span><br />")
      .append("<span>Prezzo:&nbsp;" + price + "</span><br />")
    let logoNode = $("<img />")
        .attr("class", "img-responsive center-block");

    logoNode
      .attr("style", "max-height:" + dim + ";")
      .attr("src", src)
      .attr("alt", alt)
      .attr("title", title);
    
    detailsContainerBook.append(logoNode);
    resultNode.append(detailsContainerBook);

    if (review != null) {
      let collapseNode = $("<div></div>").append(
        "<br /><br /><span><a data-toggle='collapse' href='#collapse" +
          i +
          "'>Scopri di più</a><span></div>"
      );
      detailsContainerBook.append(collapseNode);
      detailsContainerBook.append("<br /><br /><br />");

      let detailsContainerReview = $("<div></div>")
        .attr("id", "collapse" + i)
        .attr("class", "panel-collapse collapse");
      // create stats container
      let statsContainer = $("<div></div>")
        .attr("id", "stats" + i)
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
      let textContainer = $("<div></div>")
        .attr("id", "text" + i)
        .css({
          float: "right",
          width: "78%",
          margin: "6px 8px"
        })
        .append("<div><h4>Trama</h4><p>" + review.plot + "</p></div>")
        .append(
          "<div><h4>Recensione di un utente</h4><p>" + review.text + "</p></div>"
        );
      detailsContainerReview.append(textContainer);
      resultNode.append(detailsContainerReview);
    }

    resultsDiv.append(resultNode);
    resultsDiv.append("<hr />");
    resultNode.fadeIn();
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
