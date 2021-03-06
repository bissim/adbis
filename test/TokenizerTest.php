<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './util/TokensManager.php';

    use \test\BasicTest;
    use \util\TokensManager;
    use \NlpTools\Similarity\JaccardIndex;

    class TokenizerTest
        extends BasicTest
    {
        public function test()
        {
            $keyword = 'I vitelli di J. K. Rowling sono belli il mese di Gennaio';
            echo "Tokenize '$keyword'...<br />";
            echo '<br />';

            $tokMan = new TokensManager;
            $tokens = $tokMan->getTokens($keyword);
            echo 'Tokenized keyword: ';
            var_dump($tokens);
            echo '<br /><br />';

            echo 'Remove stop words...<br />';
            $tokens = $tokMan->removeStopWords($tokens);
            echo 'Removed stop words from set: ';
            var_dump($tokens);
            echo '<br /><br /><hr />';
            unset($keyword);
            unset($tokens);

            $keyword = 'Harry Potter';
            $title = 'Harry Potter e il prigioniero di Azkaban';

            echo "Checking whether '$keyword' and '$title' are similar: ";
            $this->similarityTest($tokMan, $keyword, $title);

            $keyword = 'J. K. Rowling';
            $title = 'Joanne Kathleen Rowling';

            echo "Checking whether '$keyword' and '$title' are similar: ";
            $this->similarityTest($tokMan, $keyword, $title);

            $keyword = 'Figlie Mare';
            $title = 'Eine Liebe in Apulien: Sommerroman (German Edition)';

            echo "Checking whether '$keyword' and '$title' are similar: ";
            $this->similarityTest($tokMan, $keyword, $title);
        }

        private function similarityTest(TokensManager $tokensManager, string $keyword, string $target)
        {
            $firstSet = $tokensManager->getTokens($keyword);
            $secondSet = $tokensManager->getTokens($target);
            $compareValue = (new JaccardIndex)->similarity(
                $firstSet,
                $secondSet
            );
            $result = (int) $tokensManager->compare($keyword, $target);
            echo "$result (value ";
            printf("%.3f", $compareValue);
            echo ")<br />";
        }
    }
