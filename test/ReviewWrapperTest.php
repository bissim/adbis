<?php
    /**
         * Created by PhpStorm.
         * User: bisim
         * Date: 09/09/2018
         * Time: 01:02
         */

    namespace test;

    require_once './wrappers/ReviewWrapper.php';

    use util\ErrorHandler;
    use wrappers\ReviewWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class ReviewWrapperTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
        }

        public function test()
        {
            $inizio = $this->microtime_float();

            $reviewWrapper = new ReviewWrapper;

            try
            {
                // $reviews = $reviewWrapper->getReviews('il signore degli anelli');
                $reviews = $reviewWrapper->getNewReviews();
            }
            catch (\Throwable $th)
            {
                \error_log($th->getMessage());
            }

            // check parameters for every review
            foreach ($reviews as $review)
                print $review;

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = \number_format($tempo_impiegato,5,',','.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }