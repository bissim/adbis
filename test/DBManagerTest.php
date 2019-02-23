<?php

    namespace test;

    require_once './test/BasicTest.php';
    require_once './controller/DBManager.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \controller\DBManager;

    set_error_handler(array(new ErrorHandler(), 'errorHandler'));

    class DBManagerTest
        extends BasicTest
    {
        public function test()
        {
            $this->start_time();

            $dbManager = new DBManager;

            // test db connection
            $books = $dbManager->getAllBooks();
            for ($i = 0; $i < 5; $i++)
            {
                echo "Book $i: $books[$i]";
            }

            $elapsed = $this->get_elapsed();
            echo "Tempo impiegato: $elapsed secondi";
        }
    }
