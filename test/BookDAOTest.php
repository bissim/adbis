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

$book = new Book(
    'title',
    'author',
    1.0,
    'image',
    'link'
);
$returnedBook = null;
try
{
    $returnedBook = $bookDao->create($book);
    echo "Added book: $returnedBook";
    if (!$returnedBook)
        $returnedBook = new Book(
            'just a title',
            'just an author',
            0.0,
            'wtf do u rly want an img?',
            'wat'
        );
    $returnedBook->setId(6);
    echo "PENEsearching for book with ID {$returnedBook->getId()}...<br />";
    $returnedBook = $bookDao->retrieve($book);
    echo "Book retrieved: $returnedBook";
}
catch (\Exception $e)
{
    $date = (new \DateTime())->format('Y-m-d H:i:s');
    error_log("[$date] An error occurred: {$e->getMessage()}");
}