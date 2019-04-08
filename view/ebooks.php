<?php
    namespace view;
?>

<!DOCTYPE html>

<html lang="it">

  <head>

    <title>AdBis - Ebooks</title>

    <!-- Common head tags -->
    <?php
        include 'commonhead.php';
    ?>

  </head>

  <body>

    <!-- Navigation -->
    <?php
        include 'nav.php';
    ?>

    <!-- Page Header -->
    <?php
        include 'header.php';
    ?>

    <!-- Main Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <form name="sentMessage" id="contactForm" novalidate>
            <p>Cerca ebook in base al titolo di un libro o al nome di un autore</p>
            <div class="control-group">
              <div class="form-group floating-label-form-group controls">
                <label id="searchLabel">Titolo</label>
                <input id="keyword" type="text" class="form-control" placeholder="Titolo" id="title">
                <p class="help-block text-danger"></p>
              </div>
              <input type="radio" id="searchByTitle" name="search" value="title" checked>&nbsp;Titolo
              <input type="radio" id="searchByAuthor" name="search" value="author">&nbsp;Autore
              <!-- <input type="checkbox" id="searchBoth" name="join" value="join">&nbsp;Cerca le recensioni associate -->
            </div>
            <br />
            <div class="control-group" style="margin-top:5px;">
              <button type="submit" onclick="searchBooks('cache')" class="btn btn-primary" id="sendMessageButton">Cerca</button>
            </div>
            <br/>
            <div id="success"></div>
          </form>
      <button onclick="searchBooks('scraping')" class="btn btn-primary" id="deepSearch" style="display:none">Cerca Altri Risultati</button>
            <br /><br />
        </div>
        <br />
      </div>

      <div id="resultsTitle" class="row" style="display:none;"><h3>Risultati di ricerca</h3></div>
      <div id="results" class="row" style="display:none;margin:20px 5px 10px 5px;"></div>

      <!-- Loadbox -->
      <?php
          include 'loadbox.php';
      ?>
    </div>

    <hr />
 
    <!-- Footer -->
    <?php
        include 'footer.php';
    ?>

    <!-- Common scripts -->
    <?php
        include 'commonscripts.php';
    ?>

  </body>

</html>
