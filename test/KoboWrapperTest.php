<?php

namespace test;

require '../wrappers/KoboWrapper.php';

use \wrappers\KoboWrapper;

$koboWrapper = new KoboWrapper;
// var_dump($koboWrapper->getQueries());
$books = $koboWrapper->getBooks('il signore degli anelli');

// Controllo i parametri di ogni libro
foreach ($books as $book)
    print $book;
