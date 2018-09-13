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

// Il tipo di ricerca (per autore o titolo)
$search = $obj->search;
// La chiave della ricerca (nome dell'autore o titolo del libro)
$keyword = $obj->keyword;

// Cerca i libri presenti in cache
$daoMng = new DAOManager();
$books = $daoMng->getBooks($search, $keyword);

// Se nel database non sono presenti libri attenenti alla ricerca, si effettua
// l'estrazione con i wrapper, salvando in cache i risultati dell'estrazione
if (empty($books))
{
    $wrapperMng = new WrapperManager();
    $daoMng->addBooks($wrapperMng->getBooks($keyword));
    $books = $daoMng->getBooks($search, $keyword);
}

echo json_encode($books);

?>