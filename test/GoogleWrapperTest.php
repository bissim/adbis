<?php

namespace test;

require '../wrappers/GoogleWrapper.php';

use \wrappers\GoogleWrapper;

$googleWrapper = new GoogleWrapper;
$books = $googleWrapper->getBooks('tolkien');

// Controllo i parametri di ogni libro
foreach ($books as $book)
    print $book;
