<?php

    namespace test;

    require_once './controller/DBManager.php';
    require_once './model/Book.php';
    require_once './model/Review.php';

    use \util\ErrorHandler;
    use controller\DBManager;
    use model\Book;
    use model\Review;

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
            $reviews = array(
                new Review(
                    'Title review',
                    'Review author',
                    'Yea imagine dis a plot',
                    'What? This is supposed to be the actual review',
                    4.0,
                    4.0,
                    4.0,
                    4.0
                )
                );
            $dbManager->addReviews($reviews);
            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }