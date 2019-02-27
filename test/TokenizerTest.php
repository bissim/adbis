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
            echo '<br /><br />';
            unset($keyword);
            unset($tokens);

            $keyword = 'Harry Potter';
            $title = 'Harry Potter e il prigioniero di Azkaban';

            echo "Checking whether '$keyword' and '$title' are similar: ";
            $firstSet = $tokMan->getTokens($keyword);
            $secondSet = $tokMan->getTokens($title);
            $compareValue = (new JaccardIndex)->similarity(
                $firstSet,
                $secondSet
            );
            $result = (int) $tokMan->compare($keyword, $title);
            echo "$result (value ";
            printf("%.3f", $compareValue);
            echo ")<br />";

            $keyword = 'J. K. Rowling';
            $title = 'Joanne Kathleen Rowling';

            echo "Checking whether '$keyword' and '$title' are similar: ";
            $firstSet = $tokMan->getTokens($keyword);
            $secondSet = $tokMan->getTokens($title);
            $compareValue = (new JaccardIndex)->similarity(
                $firstSet,
                $secondSet
            );
            $result = (int) $tokMan->compare($keyword, $title);
            echo "$result (value ";
            printf("%.3f", $compareValue);
            echo ")<br />";
        }
    }
