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
            $this->start_time();

            $amazonWrapper = new AmazonWrapper;
            // $books = $amazonWrapper->getBooks('harry potter');
            // $books = $amazonWrapper->getNewBooks();

            $books = array_merge(
                $amazonWrapper->getBooks('finestra mare'),
                $amazonWrapper->getNewBooks()
            );

            // check parameters for every book
            foreach ($books as $book)
                print $book;

            $elapsed = $this->get_elapsed();
            echo "Tempo impiegato dallo script: $elapsed secondi";
        }
    }
