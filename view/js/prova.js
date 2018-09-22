// Ricava in formato JSON i nuovi ebook in vendita

$(document).ready(function () {
    $.ajax({
        url: '/adbis/search/news',
        success: function(res) {
            let json = JSON.parse(res);
            showBooks(json.books);
            showReviews(json.reviews);
        }
    });
});

function showBooks(books) {
    console.debug("hi I'll show books nao");
    // console.debug("Object received: " + res);
    
    let resultsDiv = $("#ebookResults");

    try {
        // delete temporary message in results container
        resultsDiv.empty();

        // iterate over results array
        let resultNode = "";
        $.each(books, function (i, value) {
            // create result node
            resultNode = $("<div></div>")
                .attr("id", "res" + i)
                .attr("class", "row");
            resultNode.hide();

            // create image container
            let imgContainerNode = $("<div></div>")
                .attr("style", "float:right;width:200px;height:200px;margin:2px 4px 2px 20px;");
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
        });
    } catch (e) {
        throw e;
    }
}

function showReviews(reviews) {
    // console.debug("Object received: " + res);

    let resultsDiv = $("#reviewResults");

    try {
        // delete temporary message in results container
        resultsDiv.empty();

        // iterate over results array
        let resultNode = "";
        $.each(reviews, function (i, value) {
            // create results node
            resultNode = $("<div></div>")
                .attr("id", "res" + i)
                .attr("class", "row")
                .css("padding-left", "20px");
            resultNode.hide();

            // create stats container
            let statsContainer = $("<div></div>")
                .attr("style", "float:right;width:200px;height:200px;margin:2px 4px 2px 4px;");
            statsContainer
                .append("<span>Voto: <strong>" + value.average + "</strong></span><br />")
                .append("<span>Stile: " + value.style + "</span><br />")
                .append("<span>Contenuto: " + value.content + "</span><br />")
                .append("<span>Piacevolezza: " + value.pleasantness + "</span>");
            resultNode.append(statsContainer);

            // create plot and review container
            let textContainer = $("<div></div>")
                .css("float", "left")
            textContainer
                .append("<span><strong>" + value.title + "</strong></span><br />")
                .append("<span>di <em>" + value.author + "</em></span><br />")
                .append("<div><h4>Trama</h4><p>" + value.plot + "</p></div>")
                .append("<div><h4>Recensione di un utente</h4><p>" + value.txt + "</p></div>");
            resultNode.append(textContainer);

            resultsDiv.append(resultNode);
            resultsDiv.append("<hr />");
            resultNode.fadeIn();
        });
    } catch (e) {
        throw e;
    }
}