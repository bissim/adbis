<?php
    namespace view;
?>

<!DOCTYPE html>

<html lang="it">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Academic project for data integration course held by Prof. G. Costagliola">
    <meta name="author" content="Antonio Addeo and Simone Bisogno">

    <title>AdBis - Contatti</title>

    <!-- Bootstrap core CSS -->
    <link href="view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="view/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="view/css/clean-blog.min.css" rel="stylesheet">
    <link href='view/img/book.png' rel='shortcut icon' type='image/png' />

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
    </div>

    <hr />

    <!-- Footer -->
    <?php
        include 'footer.php';
    ?>

    <!-- Bootstrap core JavaScript -->
    <script src="view/vendor/jquery/jquery.min.js"></script>
    <script src="view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="view/js/clean-blog.min.js"></script>

  </body>

</html>
