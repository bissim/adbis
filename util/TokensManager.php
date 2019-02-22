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
        private $stopwords = array(
            'il' => 1,
            'lo' => 1,
            'la' => 1,
            'i' => 1,
            'gli' => 1,
            'le' => 1,
            'un' => 1,
            'uno' => 1,
            'una' => 1,
            'del' => 1,
            'della' => 1,
            'dei' => 1,
            'degli' => 1,
            'delle' => 1,
            'di' => 1,
            'a' => 1,
            'da' => 1,
            'in' => 1,
            'con' => 1,
            'su' => 1,
            'per' => 1,
            'tra' => 1,
            'fra' => 1,
            'the' => 1
        );

        /**
         * @var float Threshold value for similarity.
         */
        private $threshold = 0.6;

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
         * @param string $title
         *
         * @return bool
         */
        public function compare(string $keyword = "", string $title = ""): bool
        {
            $keywordSet = $this->removeStopWords($this->getTokens($keyword));
            $titleSet = $this->removeStopWords($this->getTokens($title));

            $value = $this->compareTokens(
                $keywordSet,
                $titleSet
            );

            //return $value >= $this->threshold? true: false;
            if ($value >= $this->threshold)
            {
                return true;
            }
            else // check whether keyword tokens are all in title
            {
                foreach ($keywordSet as $token)
                {
                    if (!in_array($token, $titleSet))
                    {
                        return false;
                    }
                }

                return true;
            }
        }
    }
