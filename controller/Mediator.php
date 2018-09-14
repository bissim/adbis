<?php
// header("Content-Type: application/json; charset=UTF-8");

require_once '../model/Book.php';
require_once '../model/Review.php';

require '../util/ErrorHandler.php';

require 'WrapperManager.php';
require 'DAOManager.php';

use model\Book;
use model\Review;

use \util\ErrorHandler;

use WrapperManager;
use DAOManager;

// Decodifa l'oggetto JSON
$obj = json_decode($_GET["x"], false);

// La tabella in cui effettuare la ricerca
$table = $obj->table;
// Il tipo di ricerca (per autore o titolo)
$search = $obj->search;
// La chiave della ricerca (nome dell'autore o titolo del libro)
$keyword = $obj->keyword;

switch($table)
{
    case 'book': jsonEncodeBooks($search, $keyword); break;
    case 'review': jsonEncodeReviews($search, $keyword);
}

function jsonEncodeBooks (string $search, string $keyword)
{
    try
    {
        $daoMng = new DAOManager();
        $books = $daoMng->getBooks($search, $keyword);
        if (empty($books))
        {
            $wrapperMng = new WrapperManager();
            $daoMng->addBooks($wrapperMng->getBooks($keyword));
            $books = $daoMng->getBooks($search, $keyword);
        }
        echo json_encode($books);        
    }
    catch (\Throwable $t)
    {
        error_log("An error occurred: {$t->getMessage()}.");
    }
}

function jsonEncodeReviews(string $search, string $keyword)
{
    try
    {
        $daoMng = new DAOManager();
        $reviews = $daoMng->getReviews($search, $keyword);
        if (empty($reviews))
        {
            $wrapperMng = new WrapperManager();
            $daoMng->addReviews($wrapperMng->getReviews($keyword));
            $reviews = $daoMng->getReviews($search, $keyword);
        }
        echo json_encode($reviews);
    }
    catch (\Throwable $t)
    {
        error_log("An error occurred: {$t->getMessage()}.");
    }
}


// try
// {
//     if (strcmp($table,'book')==0)
//     {
//         $books = $daoMng->getBooks($search, $keyword);
//         if (empty($books))
//         {
//             $wrapperMng = new WrapperManager();
//             $daoMng->addBooks($wrapperMng->getBooks($keyword));
//             $books = $daoMng->getBooks($search, $keyword);
//         }
//         echo json_encode($books);
//     }
//     else if (strcmp($table,'review')==0)
//     {
//         $reviews = $daoMng->getReviews($search, $keyword);
//         if (empty($reviews))
//         {
//             $wrapperMng = new WrapperManager();
//             $daoMng->addReviews($wrapperMng->getReviews($keyword));
//             $reviews = $daoMng->getReviews($search, $keyword);
//         }
//         echo json_encode($reviews);
//     }
// }
// catch (\Throwable $t)
// {
//     error_log("An error occurred: {$t->getMessage()}.");
// }
