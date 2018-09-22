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

            $book = new Book('title', 'author', 1.0, 'image', 'link');
            $book->setRecent(true);
            try
            {
                $returnedBooks = $bookDao->retrieveNew();
                $books = array();
                
                foreach ($returnedBooks as $returnedBook)
                {
                    $book = new Book (
                        $returnedBook["title"],
                        $returnedBook["author"],
                        $returnedBook["price"],
                        $returnedBook["image"],
                        $returnedBook["link"],
                        $returnedBook["is_recent"]
                   );
                   array_push($books, $book);
                }

                foreach ($books as $book)
                    print $book;


            }
        
            catch (\Exception $e)
            {
                $date = (new \DateTime())->format('Y-m-d H:i:s');
                error_log("[$date] An error occurred: {$e->getMessage()}");
            }
        }

    }
