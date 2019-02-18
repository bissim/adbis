<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './util/TokensManager.php';

    use \test\BasicTest;
    use \util\TokensManager;

    class TokenizerTest
        extends BasicTest
    {
        public function test()
        {
            $keyword = 'I vitelli dei romani sono belli il mese di gennaio';
            echo "Tokenize '$keyword'...<br />";
            echo '<br />';

            $tokenizer = new TokensManager;
            $tokens = $tokenizer->getTokens($keyword);
            echo 'Tokenized keyword: ';
            var_dump($tokens);
            echo '<br /><br />';

            echo 'Remove stop words...<br />';
            $tokens = $tokenizer->removeStopWords($tokens);
            echo 'Removed stop words from set: ';
            var_dump($tokens);
        }
    }
