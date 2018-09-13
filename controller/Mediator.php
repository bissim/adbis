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
$bookReturned;

$search = $obj->search;

switch ($search) {
    case 'author': $bookReturned = $bookDao->retrieveByAuthor($obj->keyword); break;
    case 'title' : $bookReturned = $bookDao->retrieveByTitle($obj->keyword);
}

echo json_encode($bookReturned);

?>