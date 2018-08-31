<?php

namespace test;

require '../wrappers/GoogleWrapper.php';
require '../model/DBManager.php';

use \wrappers\GoogleWrapper;
use \model\DBManager;

$googleWrapper = new GoogleWrapper;
$books = $googleWrapper->getBooks('tolkien');

$mng = new DBManager('localhost','phpmyadmin','pass','progettoDB');
$title = 'anelli';
$author = 'Tolkien';
$booksByTitle = $mng->getBooksByTitle($title);
$booksByAuthor = $mng->getBooksByAuthor($author);

print "<h2>Libri con '" . $title . "'</h2>";
foreach($booksByTitle as $bookByTitle)
    print $bookByTitle;
print "<h2>Libri di " . $author . "</h2>";
foreach($booksByAuthor as $bookByAuthor)
    print $bookByAuthor;
?>