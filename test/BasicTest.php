<?php
    namespace test;

    class BasicTest
    {
        private $startTime;
        private $endTime;

        public function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        public function start_time()
        {
            $this->startTime = $this->microtime_float();
        }

        public function get_elapsed(): string
        {
            $this->endTime = $this->microtime_float();
            $elapsed = $this->endTime - $this->startTime;
            $elapsed = number_format(
                $elapsed,
                5,
                ',',
                '.'
            );
            return $elapsed;
        }
    }
