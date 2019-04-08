<?php
    namespace view;
?>

<!DOCTYPE html>

<html lang="it">

  <head>

    <title>Adbis - Chi siamo</title>

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
          <p>
            Gli sviluppatori di <strong>AdBis</strong> sono <em>Antonio Addeo</em>
            e <em>Simone Bisogno</em>, studenti presso il
            <a href="http://www.di.unisa.it">Dipartimento di Informatica</a>
            dell'Università degli Studi di Salerno.
          </p>

          <p>
            Questo sito è un progetto applicativo di <em>integrazione dati</em>
            per il corso di <a href="https://corsi.unisa.it/informatica-magistrale/didattica/insegnamenti?anno=2017&id=507522">Integrazione Dati su Web</a>
            per l'anno accademico 2017-2018 tenuto dal Prof. <a href="https://rubrica.unisa.it/persone?matricola=001602">Gennaro Costagliola</a>.
          </p>

          <p>Le fonti utilizzate per questo sito sono:</p>
          <ul>
            <li>gli store online <a href="https://www.amazon.it"><em>Amazon</em></a>, <a href="https://www.kobo.com/it"><em>Kobo</em></a>, <a href="https://books.google.com/?hl=it"><em>Google Libri</em></a> per gli ebook;</li>
            <li>gli store online <a href="https://www.audible.it"><em>Audible</em></a> e <a href="https://www.ilnarratore.com/it/"><em>ilNarratore</em></a> per gli audiolibri</li>
            <li><a href="https://www.qlibri.it"><em>QLibri</em></a> per le recensioni.</li>
          </ul>

          <p>Google Libri espone delle <a href="https://developers.google.com/books/">API</a> per recuperare informazioni sui libri mentre per gli altri è stato necessario fare attività di <em>scraping</em> per il recupero dati.</p>
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
