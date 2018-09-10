<?php

namespace test;

require '../util/ErrorHandler.php';
require '../wrappers/KoboWrapper.php';
// require '../Autoloader.php';

// use Autoloader;
use \util\ErrorHandler;
use \wrappers\KoboWrapper;

set_error_handler(array(new ErrorHandler(), 'errorHandler'));

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$inizio = microtime_float();

$koboWrapper = new KoboWrapper;
$books = $koboWrapper->getBooks('harry potter');

foreach ($books as $book)
    print $book;

$fine = microtime_float();
$tempo_impiegato = $fine - $inizio;
$tempo = number_format($tempo_impiegato,5,',','.');
echo "Tempo impiegato dallo script: $tempo secondi";