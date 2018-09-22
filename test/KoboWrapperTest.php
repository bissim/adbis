<?php
    namespace test;

    require_once './wrappers/KoboWrapper.php';

    use \util\ErrorHandler;
    use \wrappers\KoboWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class KoboWrapperTest
    {
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function test()
        {
            $inizio = $this->microtime_float();

            $koboWrapper = new KoboWrapper;
            // $books = $koboWrapper->getBooks('harry potter');
            // $books = $koboWrapper->getNewBooks();

            $books = array_merge($koboWrapper->getBooks('harry potter'),
                                $koboWrapper->getNewBooks());

            foreach ($books as $book)
                print $book;

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
