<!DOCTYPE html>

<?php

// These lines are for DEVELOPMENT only.  You should never display errors
// in a production environment.
error_reporting(E_ALL);
ini_set('display_errors', '1');

// import autoloader
include '../Autoloader.php';

?>

<html>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<meta charset="utf-8" />
<title>Prova</title>

<body>

<!-- Qui saranno posizionaati i risultati della ricerca -->
<p id="demo"></p>

<script type="text/javascript" src="main.js"></script>

<h2>Libri</h2>
<!-- Il form per la ricerca dei libri in base all'autore -->
<form name="authorForm">
    Digita il nome di un autore <input type="text" id="bookAut" name="author"><br>
    <input id="bookAutBtn" type="button" value="Invia">
</form>

<!-- Il form per la ricerca dei libri in base al titolo -->
<form name="titleForm">
    Digita il titolo di un libro <input type="text" id="bookTit" name="title"><br>
    <input id="bookTitBtn" type="button" value="Invia">
</form>

<hr />

<h2>Recensioni</h2>
<!-- Il form per la ricerca delle recensioni in base all'autore -->
<form name="authorForm">
        Digita il nome di un autore <input type="text" id="reviewAut" name="author"><br>
        <input id="reviewAutBtn" type="button" value="Invia">
    </form>
    
    <!-- Il form per la ricerca delle recensioni in base al titolo -->
    <form name="titleForm">
        Digita il titolo di un libro <input type="text" id="reviewTit" name="title"><br>
        <input id="reviewTitBtn" type="button" value="Invia">
    </form>

</body>

</html>


