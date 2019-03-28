<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './wrappers/NarratoreWrapper.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \wrappers\NarratoreWrapper;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class NarratoreWrapperTest
        extends BasicTest
    {
        public function test()
        {
            $this->start_time();

            // test audiobook retrieval
            $narratoreWrapper = new NarratoreWrapper;
            $books = $narratoreWrapper->getNewBooks();
            // $books = $narratoreWrapper->getBooks("umberto eco");

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
        }
    }
