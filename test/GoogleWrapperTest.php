<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './wrappers/GoogleWrapper.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \wrappers\GoogleWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class GoogleWrapperTest
        extends BasicTest
    {
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
