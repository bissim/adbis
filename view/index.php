<?php
    namespace view;
?>

<!DOCTYPE html>

<html lang="it">

  <head>

    <title>AdBis</title>

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
      <div id="results" class="row"></div>

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
