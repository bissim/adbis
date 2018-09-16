<?php
    namespace wrappers;

    require_once './util/BookScraper.php';

    use \util\BookScraper;

    class KoboWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        private $queryUrl = 'https://www.kobo.com/it/it/search?Query=';

        public function __construct()
        {
            $this->queries = array(
                // 'links' => '//div[@class="item-info"]/p[@class="title product-field"]/a/attribute::href',
                'link' => '//section//div/ul/li/div/div[2]/p/a/attribute::href',
                'title' => '//section//div/ul/li/div/div[2]/p/a/text()',
                'author' => '//section//div/ul/li/div/div[2]/p/span[2]/a/text()',
                'price' => '//section//div/ul/li/div/div[2]/p[@class="product-field price"]/span/span/text()',
                // 'editor' => '//div[@class="BookItemDetailSecondaryMetadataWidget"]/div/div/div/ul/li/a[@class="description-anchor"]/span/text()',
                'image' => '//section//div/ul/li/div/div[1]/div/a/div/img/attribute::src',
            );
            $this->bookScraper = new BookScraper;
            $this->bookScraper->setQueries($this->queries);
        }

        public function getBooks(String $keyword): array
        {
            return $this->bookScraper->getBooks(
                '',
                $this->queryUrl,
                $keyword,
                '&fclanguages=it'
            );
        }

        public function getQueries(): array
        {
            // return $this->queries;
            return $this->bookScraper->getQueries();
        }
    }
