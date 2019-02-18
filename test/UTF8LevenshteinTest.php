<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './controller/UTF8Levenshtein.php';

    use \test\BasicTest;
    use \util\ErrorHandler;
    use \controller\UTF8Levenshtein;

    set_error_handler(array(new ErrorHandler, 'errorHandler'));

    class UTF8LevenshteinTest
        extends BasicTest
    {
        public function test()
        {
            $begin = $this->microtime_float();

            try
            {
                $utf8levenshtein = new UTF8Levenshtein;
                $string1 = "Harry Potter";
                $string2 = "Harry Potter e il prigioniero di Azkaban";

                $rate = $utf8levenshtein->levenshteinUtf8($string1, $string2);
                echo "Il punteggio di Levenshtein tra $string2 e $string2 è $rate.";
            }
            catch (\Throwable $th)
            {
                echo "An error occurred on {$th->getFile()}, check server logs.";
                error_log(
                    "An error occurred: {$th->getMessage()} " .
                    "on {$th->getFile()}, line {$th->getLine()} " .
                    " (code {$th->getCode()})."
                );
            }
        }
    }
