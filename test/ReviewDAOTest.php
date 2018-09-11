<?php
/**
 * Created by PhpStorm.
 * User: bisim
 * Date: 10/09/2018
 * Time: 09:35
 */

namespace test;

require_once '../model/Review.php';
require '../controller/ReviewDAO.php';

use model\Review;
use controller\ReviewDAO;

$reviewDao = new ReviewDAO;

$review = new Review(
    'Un titolo',
    'Un autore',
    'Una tram',
    'Un testo',
    1.0,
    2.0,
    3.0,
    1.5
);
$returnedReview = null;
try
{
    $returnedReview = $reviewDao->create($review);
    echo "Added review: $returnedReview";
    if (!$returnedReview)
        $returnedReview = new Review(
            'just a title',
            'just an author',
            'wtf do u rly want an plot?',
            'wat',
            1.3,
            5.1,
            4.1,
            2.9
        );
    $returnedReview->setId(6);
    echo "PENEsearching for review with ID {$returnedReview->getId()}...<br />";
    $returnedReview = $reviewDao->retrieve($review);
    echo "Review retrieved: $returnedReview";
}
catch (\Exception $e)
{
    $date = (new \DateTime())->format('Y-m-d H:i:s');
    error_log("[$date] An error occurred: {$e->getMessage()}");
}