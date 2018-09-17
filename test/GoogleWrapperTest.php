<?php
    namespace test;

    require_once './wrappers/GoogleWrapper.php';

    use \util\ErrorHandler;
    use \wrappers\GoogleWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class GoogleWrapperTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function test()
        {
            $inizio = $this->microtime_float();

            $googleWrapper = new GoogleWrapper;
            $books = $googleWrapper->getBooks('tolkien');

            // check parameters for every book
            foreach ($books as $book)
                print $book;

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
