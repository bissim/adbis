<?php
    namespace test;

//    require_once './controller/BookDAO.php';
//    require_once './controller/ReviewDAO.php';
//    require_once './controller/DAOManager.php';
    require_once './controller/Mediator.php';

    use \util\ErrorHandler;
//    use \controller\BookDAO;
//    use \controller\ReviewDAO;
//    use \controller\DAOManager;
    use \controller\Mediator;

    set_error_handler(array(new ErrorHandler, 'errorHandler'));

    class MediatorTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function test()
        {
            $begin = $this->microtime_float();

//            $bookDao = new BookDAO;
//            $bookReturned = $bookDao->retrieveByAuthor('aut');
//            $jsonBook = json_encode($bookReturned);
//            echo $jsonBook;
//
//            $reviewDao = new ReviewDAO;
//            $reviewReturned = $reviewDao->retrieveByAuthor('aut');
//            $jsonReview = json_encode($reviewReturned);
//            echo $jsonReview;

//            $daoMan = new DAOManager;
//
//            $books = $daoMan->getBooks('title', 'harry potter');
//            echo json_encode($books);
//
//            $reviews = $daoMan->getReviews('title', 'harry potter');
//            echo json_encode($reviews);

            try
            {
                $mediator = new Mediator;
                $jsonBooks = $mediator->retrieve('book', 'title', 'harry potter');
                $books = json_decode($jsonBooks, true);
//                echo $books;
                echo "<hr />";

                $jsonReviews = $mediator->retrieve('review', 'title', 'harry potter');
                $reviews = json_decode($jsonReviews, true);
//                echo $reviews;
                echo "<hr />";

                $jsonReviewedBooks = $mediator->retrieve('join', 'title', 'harry potter');
                $reviewedBook = json_decode($jsonReviewedBooks, true);
                print_r($reviewedBook);
                echo "<hr />";

                $end = $this->microtime_float();
                $elapsedTime = $end - $begin;
                $formattedTime = number_format($elapsedTime, 5, ',', '.');
                echo "Tempo impiegato dallo script: $formattedTime secondi";
            }
            catch (\Throwable $th)
            {
                echo "An error occurred on {$th->getFile()}, check server logs.";
                error_log(
                    "An error occurred: {$th->getMessage()} " .
                    "on {$th->getFile()}, line {$th->getLine()} " .
                    " (code {$th->getCode()})."
                );
            }
        }
    }
