<?php
    namespace util;


    /**
     * Class UTF8Levenshtein
     *
     * Credits to <strong>luciole75w</strong>
     * Source: http://it2.php.net/manual/en/function.levenshtein.php#113702
     *
     * @package util
     */
    class UTF8Levenshtein
    {
        /**
         *
         * Convert an UTF-8 encoded string to a single-byte string suitable for
         * functions such as levenshtein.
         *
         * The function simply uses (and updates) a tailored dynamic encoding
         * (in/out map parameter) where non-ascii characters are remapped to
         * the range [128-255] in order of appearance.
         *
         * Thus it supports up to 128 different multibyte code points max over
         * the whole set of strings sharing this encoding.
         *
         * @param $str
         * @param $map
         *
         * @return string
         */
        public function utf8ToExtendedAscii($str, &$map)
        {
            // find all multibyte characters (cf. utf-8 encoding specs)
            $matches = array();
            if (!preg_match_all('/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches))
            {
                return $str; // plain ascii string
            }

            // update the encoding map with the characters not already met
            foreach ($matches[0] as $mbc)
            {
                if (!isset($map[$mbc]))
                {
                    $map[$mbc] = chr(128 + count($map));
                }
            }

            // finally remap non-ascii characters
            return strtr($str, $map);
        }

        /**
         *
         * Didactic example showing the usage of the previous conversion function but,
         * for better performance, in a real application with a single input string
         * matched against many strings from a database, you will probably want to
         * pre-encode the input only once.
         *
         * @param $s1
         * @param $s2
         *
         * @return int
         */
        public function levenshteinUtf8($s1, $s2)
        {
            $charMap = array();
            $s1 = $this->utf8ToExtendedAscii($s1, $charMap);
            $s2 = $this->utf8ToExtendedAscii($s2, $charMap);

            return levenshtein($s1, $s2);
        }
    }
