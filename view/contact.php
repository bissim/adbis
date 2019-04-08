<?php
    namespace view;
?>

<!DOCTYPE html>

<html lang="it">

  <head>

    <title>AdBis - Contatti</title>

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
        <div class="col-lg-8 col-md-10 mx-auto">
          <p>Per informazioni sul sito è possibile consultare gli sviluppatori ai seguenti indirizzi email:</p>
          <div>
            <span>Antonio Addeo</span>
            <pre>a [DOT] addeo5 [AT] studenti [DOT] unisa [DOT] it</pre>
          </div>
          <div>
            <span>Simone Bisogno</span>
            <pre>s [DOT] bisogno10 [AT] studenti [DOT] unisa [DOT] it</pre>
          </div>
        </div>
      </div>
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
