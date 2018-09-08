<?php

namespace test;

require '../util/ErrorHandler.php';
require '../wrappers/AmazonWrapper.php';
// require '../Autoloader.php';

// use Autoloader;
use \util\ErrorHandler;
use \wrappers\AmazonWrapper;

set_error_handler(array(new ErrorHandler(), 'errorHandler'));

//$keyword = 'il signore degli anelli';

$amazonWrapper = new AmazonWrapper;
// var_dump($amazonWrapper->getQueries());
$books = $amazonWrapper->getBooks('il signore degli anelli');

// check parameters for every book
 foreach ($books as $book)
     print $book;
