<?php
    namespace view;
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Prova</title>
        <script src="./view/vendor/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="./view/main.js"></script>
    </head>

    <body>
        <!-- Qui saranno posizionati i risultati della ricerca -->
        <p id="demo"></p>

        <h2>Libri</h2>
        <!-- Il form per la ricerca dei libri in base all'autore -->
        <form name="authorForm">
            <p>Digita il nome di un autore</p>
            <label for="bookAut">Autore: </label>
            <input type="text" id="bookAut" name="author">
            <input id="bookAutBtn" type="button" value="Invia">
        </form>

        <!-- Il form per la ricerca dei libri in base al titolo -->
        <form name="titleForm">
            <p>Digita il titolo di un libro</p>
            <label for="bookTit">Titolo: </label>
            <input type="text" id="bookTit" name="title">
            <input id="bookTitBtn" type="button" value="Invia">
        </form>

        <hr />

        <h2>Recensioni</h2>
        <!-- Il form per la ricerca delle recensioni in base all'autore -->
        <form name="authorForm">
            <p>Digita il nome di un autore</p>
            <label for="reviewAut">Autore: </label>
            <input type="text" id="reviewAut" name="author">
            <input id="reviewAutBtn" type="button" value="Invia">
        </form>

        <!-- Il form per la ricerca delle recensioni in base al titolo -->
        <form name="titleForm">
            <p>Digita il titolo di un libro</p>
            <label for="reviewTit">Titolo: </label>
            <input type="text" id="reviewTit" name="title">
            <input id="reviewTitBtn" type="button" value="Invia">
        </form>
    </body>
</html>
