<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './wrappers/AmazonWrapper.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \wrappers\AmazonWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class AmazonWrapperTest
        extends BasicTest
    {
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
