<?php
    /**
     * Created by PhpStorm.
     * User: bisim
     * Date: 10/09/2018
     * Time: 09:35
     */

    namespace test;

    require_once './model/Book.php';
    require_once './controller/BookDAO.php';

    use \model\Book;
    use \controller\BookDAO;

    class BookDAOTest
    {
        public function test()
        {
            $bookDao = new BookDAO;

            $book = new Book(
                'title',
                'author',
                1.0,
                'image',
                'link'
            );

            $recentBook = new Book(
                'New title',
                'New Author',
                10.,
                'https://i.mg/img.png',
                'http://li.nk',
                true
            );

            $returnedBook = null;
            try
            {
                // test persisting book
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
                echo "searching for book with ID {$returnedBook->getId()}...<br />";
                $returnedBook = $bookDao->retrieveById($returnedBook);
                echo "Book retrieved: $returnedBook";

                // test persisting new book
                $returnedBook = $bookDao->create($recentBook);
                echo "<hr />Added new book: $returnedBook";
                if (!$returnedBook)
                    $returnedBook = new Book(
                        'just a title',
                        'just an author',
                        0.0,
                        'wtf do u rly want an img?',
                        'wat'
                    );
                $returnedBook->setId(7);
                echo "searching for new book with ID {$returnedBook->getId()}...<br />";
                $returnedBook = $bookDao->retrieveById($returnedBook);
                echo "Book retrieved: $returnedBook";
            }
            catch (\Exception $e)
            {
                $date = (new \DateTime())->format('Y-m-d H:i:s');
                echo "Code {$e->getCode()} error occurred, check server logs.";
                error_log(
                    "[$date] An error occurred: {$e->getMessage()} " .
                    "on file {$e->getFile()}, line {$e->getLine()}."
                );
            }
        }
    }
