<?php

namespace test;

require '../wrappers/AmazonWrapper.php';

use \wrappers\AmazonWrapper;

$amazonWrapper = new AmazonWrapper;
// var_dump($amazonWrapper->getQueries());
$books = $amazonWrapper->getBooks('il signore degli anelli');

// Controllo i parametri di ogni libro
foreach ($books as $book)
    print $book;
