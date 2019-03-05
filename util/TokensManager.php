<?php
    namespace util;

    use \NlpTools\Similarity\JaccardIndex;

    class TokensManager
    {
        /**
         * Words to be removed.
         * (words used as keys for better performance)
         *
         * @var array Words to be removed.
         */
        private $stopwords;

        /**
         * @var float Threshold value for similarity.
         */
        private $threshold = 0.5;

        public function __construct()
        {
            $this->initializeStopWords();
        }

        private function initializeStopWords()
        {
            if (!isset($this->stopwords)) // no stop words has been loaded yet
            {
                // read data from file
                $stopWordsFile = './util/stopwords.txt';
                $fh = fopen($stopWordsFile, 'r');
                $data = fread($fh, filesize($stopWordsFile));
                fclose($fh);

                // save data into associative file
                $this->stopwords = array();
                $tempArray = explode("\n", $data);
                foreach ($tempArray as $line)
                {
                    $tmp = explode(" ", $line);
                    $this->stopwords[$tmp[0]] = $tmp[1];
                }
            }
        }

        /**
         * Splits a string into tokens; split occurs over spaces.
         * @param string $keyword
         *
         * @return array
         */
        public function getTokens(string $keyword): array
        {
            /**
             * [^-\w\'] matches characters, that are not [0-9a-zA-Z_-']
             * if input is unicode/utf-8, the u flag is needed: /pattern/u
             *
             * @var array An array of tokens
             */
            $words = preg_split(
                '/[^-\w\']+/',
                $keyword,
                -1,
                PREG_SPLIT_NO_EMPTY
            );

            return $words;
        }

        /**
         * Remove stop words from an array of strings.
         * @param array $words
         *
         * @return array
         */
        public function removeStopWords(array $words): array
        {
            $sws = &$this->stopwords;
            $this->lowercaseTokens($words);

            // remove words with length less or equal than 1
            $words = array_filter(
                $words,
                function ($w)
                {
                    return (strlen($w) > 1);
                }
            );

            // if we have at least 2 words, remove stopwords
            if (count($words) > 1)
            {
                $words = array_filter(
                    $words,
                    function ($w) use ($sws)
                    {
                        // if utf-8: mb_strtolower($w, "utf-8")
                        return !isset($sws[strtolower($w)]);
                    }
                );
            }

            return $words;
        }

        /**
         * @param array $tokenSet The token set whose entries
         * have to be turned lower case.
         */
        private function lowercaseTokens(array &$tokenSet)
        {
            for ($index = 0; $index < count($tokenSet); $index++)
            {
                $tokenSet[$index] = strtolower($tokenSet[$index]);
            }
        }

        /**
         * @param array $firstSet
         * @param array $secondSet
         *
         * @return float
         */
        public function compareTokens(array $firstSet, array $secondSet): float
        {
            return (new JaccardIndex)->similarity(
                $firstSet,
                $secondSet
            );
        }

        /**
         * @param string $keyword
         * @param string $target
         *
         * @return bool
         */
        public function compare(string $keyword = "", string $target = ""): bool
        {
            $keywordSet = $this->removeStopWords($this->getTokens($keyword));
            $targetSet = $this->removeStopWords($this->getTokens($target));

            $value = $this->compareTokens(
                $keywordSet,
                $targetSet
            );

            //return $value >= $this->threshold? true: false;
            if ($value >= $this->threshold)
            {
                return true;
            }
            else // check whether keyword tokens are all in title
            {
                return (
                    $this->isContained($keywordSet, $targetSet) ||
                    $this->isContained($targetSet, $keywordSet)
                );
            }

        }

        /**
         * Check whether tokens from keyword string are
         * contained into target string.
         *
         * @param string $keyword
         * @param string $target
         *
         * @return bool
         */
        public function isTokenContained(string $keyword, string $target): bool
        {
            return $this->isContained(
                $this->getTokens($keyword),
                $this->getTokens($target)
            );
        }

        /**
         * check if all words in set1 are in set2
         *
         * @param $set1 - First set of words
         * @param $set2 - Second set of words
         *
         * @return bool
         */
        private function isContained(array $set1, array $set2): bool
        {
            foreach ($set1 as $s1)
            {
                if (!in_array($s1, $set2))
                {
                    return false;
                }
            }
            return true;
        }
    }
