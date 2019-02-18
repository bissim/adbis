<?php
    namespace test;

    require_once './test/BasicTest.php';

    use \test\BasicTest;

    class ParseIniTest
        extends BasicTest
    {
        public function test()
        {
            $configs = parse_ini_file('../db/dbconfig.ini', true);

            $serverProperty = $configs['connection']['server'];

            user_error("DB Server: {$serverProperty}");

            echo 'Parse successful!';
        }
    }
