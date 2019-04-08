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
    <main class="container">
      <div class="row">
        <h2>Ebook</h2>
      </div>
      <div class="row">
        <p>Cerca ebook in base al titolo di un libro o al nome di un autore</p>
      </div>
      <div class="row">
        <?php
            include 'searchform.php';
        ?>
        <br />
      </div>

      <div id="resultsTitle" class="row" style="display:none;"><h3>Risultati di ricerca</h3></div>
      <div id="results" class="row" style="display:none;margin:20px 5px 10px 5px;"></div>

      <!-- Loadbox -->
      <?php
          include 'loadbox.php';
      ?>
    </main>

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
