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

    <title>Adbis - Chi siamo</title>

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
          <p>Gli sviluppatori di <strong>AdBis</strong> sono <em>Antonio Addeo</em> e <em>Simone Bisogno</em>, studenti presso il <a href="http://www.di.unisa.it">Dipartimento di Informatica</a> dell'Università degli Studi di Salerno.</p>
          <p>Questo sito è un progetto applicativo di <em>integrazione dati</em> per il corso di <a href="https://corsi.unisa.it/informatica-magistrale/didattica/insegnamenti?anno=2017&id=507522">Integrazione Dati</a> su Web per l'anno accademico 2017-2018 tenuto dal Prof. <a href="https://rubrica.unisa.it/persone?matricola=001602">Gennato Costagliola</a>.</p>
          <p>Le fonti utilizzate per questo sito sono lo store online <a href="https://www.amazon.it"><em>Amazon</em></a>, lo store online <a href="https://www.kobo.com/it"><em>Kobo</em></a>, <a href="https://books.google.com/?hl=it"><em>Google Libri</em></a> e <a href="https://www.qlibri.it"><em>QLibri</em></a>; le prime tre fonti sono state interrogate per recuperare informazioni sul sito mentre l'ultima per informazioni sulle recensioni.</p>
          <p>Google Libri espone delle <a href="https://developers.google.com/books/">API</a> per recuperare informazioni sui libri mentre per gli altri è stato necessario fare attività di <em>scraping</em> per il recupero dati.</p>
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
