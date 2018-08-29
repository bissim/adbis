<?php

namespace wrappers;

require '..\model\Book.php';

use \model\Book;

function test()
{
    echo 'Print a book<br />';
    $book = new Book('Il signore degli anelli', 'J. R. R. Tolkien', 0.5, '', '', 'PENIS EDITION');
    var_dump($book);
    echo $book;
    echo '<br />Book printed!';
}

test();
