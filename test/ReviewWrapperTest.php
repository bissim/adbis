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

$reviewWrapper = new ReviewWrapper;

$reviews = $reviewWrapper->getReviews('il signore degli anelli');

// check parameters for every review
foreach ($reviews as $review)
    print $review;
