<?php
header("Content-Type: application/json; charset=UTF-8");

require_once '../model/Book.php';
require '../controller/BookDAO.php';

require_once '../model/Review.php';
require '../controller/ReviewDAO.php';

use model\Book;
use controller\BookDAO;

use model\Review;
use controller\ReviewDAO;

$bookDao = new BookDAO;
$bookReturned = $bookDao->retrieveByAuthor('aut');
//echo json_encode($bookReturned);

$reviewDao = new ReviewDAO;
$reviewReturned = $reviewDao->retrieveByAuthor('aut');
echo json_encode($reviewReturned);
