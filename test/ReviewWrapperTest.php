<?php
/**
     * Created by PhpStorm.
     * User: bisim
     * Date: 09/09/2018
     * Time: 01:02
     */

namespace test;

require '../util/ErrorHandler.php';
require '../wrappers/ReviewWrapper.php';

use util\ErrorHandler;
use wrappers\ReviewWrapper;

set_error_handler(array(new ErrorHandler(), 'errorHandler'));

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$inizio = microtime_float();

$reviewWrapper = new ReviewWrapper;

// $reviews = $reviewWrapper->getReviews('il signore degli anelli');

$reviews = $reviewWrapper->getReviews('il signore degli anelli');

// check parameters for every review
foreach ($reviews as $review)
    print $review;

$fine = microtime_float();
$tempo_impiegato = $fine - $inizio;
$tempo = number_format($tempo_impiegato,5,',','.');
echo "Tempo impiegato dallo script: $tempo secondi";