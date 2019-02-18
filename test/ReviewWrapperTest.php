<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './wrappers/ReviewWrapper.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \wrappers\ReviewWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class ReviewWrapperTest
        extends BasicTest
    {
        public function test()
        {
            $inizio = $this->microtime_float();

            $reviewWrapper = new ReviewWrapper;

            try
            {
                $reviews = $reviewWrapper->getReviews('harry potter');
                // $reviews = $reviewWrapper->getNewReviews();
                // $reviews = array_merge($reviewWrapper->getReviews('il signore degli anelli'),
                //                         $reviewWrapper->getNewReviews());
                foreach ($reviews as $review)
                    print $review;            
            }
            catch (\Throwable $th)
            {
                \error_log($th->getMessage());
            }

            // check parameters for every review


            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = \number_format($tempo_impiegato,5,',','.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }