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

            $audibleWrapper = new AudibleWrapper;
            $books = $audibleWrapper->getBooks('il suggeritore');
            // $books = $audibleWrapper->getNewBooks();

            $count = count($books);
            echo "$count libri trovati!<br /><br />";

            foreach ($books as $book)
                print $book;

            $tempo = $this->get_elapsed();
            echo "Tempo impiegato dallo script: $tempo secondi";
        }
    }
