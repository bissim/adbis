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
            $this->start_time();

            // test audiobook retrieval
            $audibleWrapper = new AudibleWrapper;
            $books = $audibleWrapper->getBooks('il suggeritore');
            // $books = $audibleWrapper->getNewBooks();

            $count = count($books);
            echo "$count libri trovati!<br /><br />";

            foreach ($books as $book)
                print $book;

            $tempo = $this->get_elapsed();
            unset($books);
            echo "Tempo impiegato dallo script: $tempo secondi<br />";
            echo "<hr />";

            // test new audiobook retrieval
            echo "Retrieving new books...<br />";
            $this->start_time();

//            $books = $audibleWrapper->getNewBooks();
            $books = $audibleWrapper->getBooks('', true);

            $count = count($books);
            echo "$count libri trovati!<br /><br />";

            foreach ($books as $book)
                print $book;

            $tempo = $this->get_elapsed();
            unset($books);
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
