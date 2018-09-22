<?php
    namespace test;

    require_once './wrappers/AmazonWrapper.php';

    use \util\ErrorHandler;
    use \wrappers\AmazonWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class AmazonWrapperTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function test()
        {
            $inizio = $this->microtime_float();

            $amazonWrapper = new AmazonWrapper;
            // $books = $amazonWrapper->getBooks('harry potter');
            // $books = $amazonWrapper->getNewBooks();

            $books = array_merge($amazonWrapper->getBooks('harry potter'),
                                $amazonWrapper->getNewBooks());

            // check parameters for every book
            foreach ($books as $book)
                print $book;

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
