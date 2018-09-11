<?php
/**
 * Created by PhpStorm.
 * User: bisim
 * Date: 10/09/2018
 * Time: 09:35
 */

namespace test;

require_once '../model/Book.php';
require '../controller/BookDAO.php';


use model\Book;
use controller\BookDAO;

$bookDao = new BookDAO;

$book = new Book('title', 'author', 1.0, 'image', 'link', 'editor');
$returnedBook = null;
try
{
//    $returnedBook = $bookDao->create($book);
//    echo "Added book: $returnedBook";
    if (!$returnedBook)
        $returnedBook = new Book('', '', 0.0, '', '', '');
    $returnedBook->setId(6);
    echo "searching for book $returnedBook...<br />";
    $returnedBook = $bookDao->retrieve($book);
    echo "Book retrieved: $returnedBook";
}
catch (\Exception $e)
{
    $date = new \DateTime();
    error_log("[$date] An error occurred: $e->getMessage()");
}
