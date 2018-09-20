<?php
    namespace wrappers;

    require_once './util/BookScraper.php';

    use \util\BookScraper;

    class KoboWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        private $queryUrl = 'https://www.kobo.com/it/it/search?fclanguages=it&Query=';

        public function __construct()
        {
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $imgQueries = array();
            $priceQueries = array();

            for ($i=1; $i<=12; $i++)
            {
                array_push($titleQueries,
                '//section//div/ul/li[' . $i . ']/div/div[2]/p/a/text()');

                array_push($linkQueries, 
                '//section//div/ul/li[' . $i . ']/div/div[2]/p/a/attribute::href');

                array_push($authorQueries,
                '//section//div/ul/li[' . $i . ']/div/div[2]/p/span[2]/a/text()');

                array_push($imgQueries,
                '//section//div/ul/li[' . $i . ']/div/div[1]/div/a/div/img/attribute::src');

                array_push($priceQueries,
                '//section//div/ul/li[' . $i . ']/div/div[2]/p[@class="product-field price"]/span/span/text()');
            }

            $this->queries = array();
            $this->queries['titleQueries'] = $titleQueries;
            $this->queries['linkQueries'] = $linkQueries;
            $this->queries['authorQueries'] = $authorQueries;
            $this->queries['imgQueries'] = $imgQueries;
            $this->queries['priceQueries'] = $priceQueries;

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
