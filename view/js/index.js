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
  $.ajax({
      url: baseSearchUrl + "news",
    //   success: showBoth
    success: showBooks
});

    // block form submission from reloading page
    $("form#contactForm").submit(function(event) {
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
  console.debug("hi I'll show books nao");
  let json = JSON.parse(res);
  console.debug("Object received: length " + Object.keys(json).length);

  let loadingMessage = $("p#loadingMessage");
  loadingMessage.remove();

  if (Object.keys(json).length>0) {
    let message =
      "La ricerca ha ottenuto dei risultati! Consultare l'elenco sottostante.";
    $("#success").html(message);
    
    try {
      // delete temporary message in results container
      let resultsDiv = $("#results");
      resultsDiv.empty();
  
      // show results
      createBookNodes(json, resultsDiv);
    } catch (e) {
      throw e;
    }
  }
}

function createBookNodes(json, resultsDiv) {
  // iterate over results array
  $.each(json, function(i, value) {
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
      .attr("src", value["img"])
      .attr("style", "max-width:190px;max-height:190px;");
    imgContainerNode.append(imgNode);
    resultNode.append(imgContainerNode);

    // create details container
    let detailsContainerNode = $("<div></div>");
    detailsContainerNode
      .append(
        "<span><a href='" +
          value["link"] +
          "'><span><strong>" +
          value["title"] +
          "</strong></span></a></span><br />"
      )
      .append("<span>di&nbsp;<em>" + value["author"] + "</em></span><br />")
      .append("<span>Prezzo:&nbsp;" + value["price"] + "&euro;</span><br />");
    resultNode.append(detailsContainerNode);

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
