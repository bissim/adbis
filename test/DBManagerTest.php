<?php

    namespace test;

    require_once './controller/DBManager.php';
    require_once './model/Book.php';
    require_once './model/Review.php';
    require_once './wrappers/GoogleWrapper.php';
    require_once './wrappers/ReviewWrapper.php';

    use \util\ErrorHandler;
    use controller\DBManager;
    use model\Book;
    use model\Review;
    use \wrappers\GoogleWrapper;
    use wrappers\ReviewWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class DBManagerTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function test()
        {
            $inizio = $this->microtime_float();

            $dbManager = new DBManager;
            
            $reviewWrapper = new ReviewWrapper;
            $reviews = array_merge($reviewWrapper->getReviews('il signore degli anelli'),
            $reviewWrapper->getNewReviews());

            $dbManager->addReviews($reviews);

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }