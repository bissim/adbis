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

$obj = json_decode($_GET["x"], false);

$bookDao = new BookDAO;

$bookReturned = $bookDao->retrieveByTitle($obj->title);
echo json_encode($bookReturned);

// $bookReturned = $bookDao->retrieveByAuthor($obj->author);
// echo json_encode($bookReturned);

?>