<?php
    namespace controller;

    class StringComparator {


        public function __construct() {}

        public function compare(string $s1, string $s2): bool {
            return strtolower($s1) === strtolower($s2);

        }

    }