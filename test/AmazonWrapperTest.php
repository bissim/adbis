<?php

namespace test;

require '../wrappers/AmazonWrapper.php';
// require '../Autoloader.php';

// use Autoloader;
use \wrappers\AmazonWrapper;

//$keyword = 'il signore degli anelli';

$amazonWrapper = new AmazonWrapper;
// var_dump($amazonWrapper->getQueries());
$books = $amazonWrapper->getBooks('il signore degli anelli');

// check parameters for every book
 foreach ($books as $book)
     print $book;
