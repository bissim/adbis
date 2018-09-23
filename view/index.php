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

    <title>AdBis</title>

    <!-- Bootstrap core CSS -->
    <link href="./view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="./view/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="./view/css/clean-blog.min.css" rel="stylesheet">
    <link href='./view/img/book.png' rel='shortcut icon' type='image/png' />

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="./">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./ebooks">Ebooks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./reviews">Recensioni</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('./view/img/home-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1>AdBis</h1>
              <span class="subheading">AdBis - Academic project for data integration course held by Prof. G. Costagliola</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <h2>Nuovi eBook</h2>
            <div id="ebookResults"></div>

            <h2>Nuove recensioni</h2>
            <div id="reviewResults"></div>
        </div>
    </div>

    <hr />

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <ul class="list-inline text-center">
              <li class="list-inline-item">
                <a href="https://github.com/bissim/adbis.git">
                  <span class="fa-stack fa-lg">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                  </span>
                </a>
              </li>
            </ul>
            <p class="copyright text-muted">Copyright &copy; AdBis 2018</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="./view/vendor/jquery/jquery.min.js"></script>
    <script src="./view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="./view/js/clean-blog.min.js"></script>

    <script src="./view/js/prova.js"></script>

  </body>

</html>
