<!DOCTYPE html>

<?php

// These lines are for DEVELOPMENT only.  You should never display errors
// in a production environment.
error_reporting(E_ALL);
ini_set( 'display_errors','1');

// import autoloader
include '../Autoloader.php';

?>

<html>

<head>
    <meta charset="utf-8" />
    <title>Prova</title>
</head>

<body>

    <form action="main.php" method="post">
        Digita il nome di un autore o di un libro <input type="text" name="author"><br>
        <input type="submit">
    </form>

</body>

</html>
