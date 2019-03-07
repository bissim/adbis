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
$(document).ready(function () {
  $("div#resLoad").show();

  // set current page name
  let pageName = determinePageName();
  // console.debug("Hi u'r in " + pageName);
  
  // async call to retrieve results
  $.ajax({
      url: baseSearchUrl + "news",
    //   success: showBoth
    success: showBooks
});

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
 * Prepare results container
 */
function prepareForResults() {
  // console.debug("Preparing for results...");

  // let resultsContainerDiv = $("#resultsContainer");
  let resultsDiv = $("#results");

  // hide and delete former results
  if (!resultsDiv.hidden) {
    resultsDiv.hide();
    let loadingMessage = $("<p>Caricamento dei risultati in corso...</p>").attr(
      "id",
      "loadingMessage"
    );
    resultsDiv.append(loadingMessage);
    $("div#resultsTitle").show();
    resultsDiv.show();
  }
}

/**
 * Show results for book query in page.
 * @param res
 */
function showBooks(res) {
  // console.debug("hi I'll show books nao");
  let json = JSON.parse(res);
  // console.debug("Object received: length " + Object.keys(json).length);
  // console.debug(res);

  let loadingMessage = $("p#loadingMessage");
  loadingMessage.remove();

  if (Object.keys(json).length > 0) {
    let message =
      "La ricerca ha ottenuto dei risultati!" +
      "Consultare l'elenco sottostante.";
    $("#success").html(message);
    
    try {
      // delete temporary message in results container
      let resultsDiv = $("#results");
      resultsDiv.empty();
  
      // show results
      createBookNodes(json['ebooks'], resultsDiv);
      createAuBookNodes(json['aubooks'], resultsDiv);
    } catch (e) {
      throw e;
    }
  }
}

function createBookNodes(json, resultsDiv) {
  $("div#resLoad").hide();
  // iterate over results array
  $.each(json, function (i, value) {
    let resultNode = "";
    
    // create result node
    resultNode = $("<div></div>")
      .attr("id", "res" + i)
      .attr("class", "container")
      .attr("style", "margin-bottom:50px");
    resultNode.hide();

    // create image container
    let imgContainerNode = $("<div></div>").attr(
      "style",
      "float:left;width:200px;height:200px;margin:2px 4px 2px 4px;"
    );
    let imgNode = $("<img />")
      .attr("class", "img-responsive center-block")
      .attr("src", value["img"]);
    let source = value["source"];
    if (source !== 'amazon') {
      imgNode.attr("style", "max-width:190px;max-height:190px;margin-left:36px;margin-right:37px;");
    } else {
      imgNode.attr("style", "max-width:190px;max-height:190px;")
    }
    imgContainerNode.append(imgNode);
    resultNode.append(imgContainerNode);

    // create details container
    let bookAuthor = (value["author"] !== '')? value["author"]: "AA. VV.";
    let detailsContainerNode = $("<div></div>");
    detailsContainerNode
      .append(
        "<span><a href='" +
          value["link"] +
          "'><span><strong>" +
          value["title"] +
          "</strong></span></a></span><br />"
      )
      .append("<span>di&nbsp;<em>" + bookAuthor + "</em></span><br />")
      .append("<span>Prezzo:&nbsp;" + value["price"] + "&euro;</span><br />");
    let logoNode = $("<img />")
      .attr("class", "img-responsive center-block")
      .attr("style", "max-height:20px;");
    switch (source) {
      case 'amazon':
        logoNode
          .attr("src", "./view/img/amazon_logo.png")
          .attr("alt", "Amazon")
          .attr("title", "Amazon");
        break;
      case 'kobo':
        logoNode
          .attr("src", "./view/img/kobo_logo.png")
          .attr("alt", "Kobo")
          .attr("title", "Kobo");
        break;
      case 'google':
        logoNode
          .attr("src", "./view/img/google_logo.png")
          .attr("alt", "Google Libri")
          .attr("title", "Google Libri");
        break;
      default:
        console.warn("Unknown source '" + source + "'!");
        break;
    }
    detailsContainerNode.append(logoNode);
    resultNode.append(detailsContainerNode);

    resultsDiv.append(resultNode);
    resultsDiv.append("<hr />");
    resultNode.fadeIn();
  });
}

function createAuBookNodes(json, resultsDiv) {
  $.each(json, function (i, item) {
    
    let resultNode = "";

    // create result node
    resultNode = $("<div></div>")
      .attr("id", "resultNode" + (i + 1))
      .attr("class", "container")
      .attr("style", "margin-bottom:50px");
    resultNode.hide();

    // create image container
    let imgContainerNode = $("<div></div>").attr(
      "style",
      "float:left;width:200px;height:200px;margin:2px 4px 2px 4px;"
    );
    let imgNode = $("<img />")
      .attr("class", "img-responsive center-block")
      .attr("src", item["img"])
      .attr("style", "max-width:190px;max-height:190px;");
    imgContainerNode.append(imgNode);
    resultNode.append(imgContainerNode);

    // create details container
    let bookAuthor = (item["author"] !== '')? item["author"]: "AA. VV.";
    let detailsContainerItem = $("<div></div>");
    detailsContainerItem
      .append(
        "<span><a href='" +
          item["link"] +
          "'><span><strong>" +
          item["title"] +
          "</strong></span></a></span><br />"
      )
      .append("<span>di&nbsp;<em>" + bookAuthor + "</em></span><br />")
      .append("<span>letto da&nbsp;<em>" + item["voice"] + "</em></span><br />");
    let logoNode = $("<img />")
      .attr("class", "img-responsive center-block")
      .attr("style", "max-height:20px;")
      .attr("src", "./view/img/audible_logo.png")
      .attr("alt", "Audible")
      .attr("title", "Audible");
    detailsContainerItem.append(logoNode);
    resultNode.append(detailsContainerItem);

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
