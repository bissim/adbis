<?php
    namespace test;
    
    require_once './controller/Mediator.php';
    require_once './controller/DBManager.php';

    use \util\ErrorHandler;
    use \controller\Mediator;
    use \controller\DBManager;
    
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

            try
            {
                $mediator = new Mediator;
                echo $mediator->getNewItems();
             
                // $jsonBooks = $mediator->retrieve('book', 'title', 'harry potter');
                // $books = json_decode($jsonBooks, true);
//                echo $books;
                // echo "<hr />";

//                 $jsonReviews = $mediator->retrieve('review', 'title', 'harry potter');
//                 $reviews = json_decode($jsonReviews, true);
// //                echo $reviews;
//                 echo "<hr />";

//                 $jsonReviewedBooks = $mediator->retrieve('join', 'title', 'harry potter');
//                 $reviewedBook = json_decode($jsonReviewedBooks, true);
//                 print_r($reviewedBook);
//                 echo "<hr />";

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
