<?php
    namespace test;

    require_once './model/Book.php';

    use \model\Book;

    class BookTest
    {
        public function test()
        {
            echo 'Print a book<br />';
            $book = new Book(
                'Il signore degli anelli',
                'J. R. R. Tolkien',
                0.5,
                '',
                ''
            );
            var_dump($book);
            echo $book;
            echo '<br />Book printed!<hr />';

            echo 'Print a new book<br />';
            $newBook = new Book(
                'This is really a new book',
                'Penito Hashigawa',
                0.01,
                'img? rly?',
                'http://de.rp',
                true
            );
            print_r($newBook);
            echo $newBook;
            echo '<br />New book printed!<hr />';
        }
    }
