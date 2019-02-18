<?php
    namespace test;

    class BasicTest
    {
        public function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }
    }
