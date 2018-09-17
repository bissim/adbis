<?php
    namespace wrappers;

    require './util/ReviewScraper.php';

    use \util\ReviewScraper;

    class ReviewWrapper
    {
        // variables
        private $reviewScraper;
        private $queries;
        private $baseUrl = 'https://www.startpage.com/do/search?language=italiano&query=site%3Aqlibri.it+';

        public function __construct()
        {
            $this->queries = array(
                'links' => '//h3[@class="clk"]/a/attribute::href',
                'title' => '//div[@id="system"]/article/div/div/h1/span[@class="fn"]/text()',
                'author' => '//div[@class="jr_customFields"]/div/div[@class="fieldRow jr_autorestr"]/div/a/text()',
                'plot' => '//div[@class="contentFulltext"]/text()',
                'text' => '//div[@id="jr_user_reviews"]/div[2]/div/div[3]/div[1]/p',
                'avg' => '//div[@class="ratingInfo"]/table/tr[1]/td[@class="rating_value"]/text()',
                'style' => '//div[@class="ratingInfo"]/table/tr[2]/td[@class="rating_value"]/text()',
                'content' => '//div[@class="ratingInfo"]/table/tr[3]/td[@class="rating_value"]/text()',
                'pleasantness' => '//div[@class="ratingInfo"]/table/tr[4]/td[@class="rating_value"]/text()'
            );
            $this->reviewScraper = new ReviewScraper;
            $this->reviewScraper->setQueries($this->queries);
        }

        public function getReviews(string $keyword): array
        {
            return $this->reviewScraper->getReviews(
                $this->baseUrl,
                $keyword,
                ''
            );
        }

        public function getQueries(): array
        {
            return $this->reviewScraper->getQueries();
        }
    }
