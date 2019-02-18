<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './wrappers/AudibleWrapper.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \wrappers\AudibleWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class AudibleWrapperTest
        extends BasicTest
    {
        public function test()
        {
            $inizio = $this->microtime_float();

            $audibleWrapper = new AudibleWrapper;
            $books = $audibleWrapper->getBooks('il suggeritore');
            // $books = $audibleWrapper->getNewBooks();

            // $books = array_merge($koboWrapper->getBooks('harry potter'),
            //                     $koboWrapper->getNewBooks());

            foreach ($books as $book)
                print $book;

            $fine = $this->microtime_float();
            $tempo_impiegato = $fine - $inizio;
            $tempo = number_format($tempo_impiegato, 5, ',', '.');
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
