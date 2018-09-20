<?php
    namespace wrappers;

    require_once './util/BookScraper.php';

    use \util\BookScraper;

    class AmazonWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        private $domain = '';
        private $queryUrl = 'https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=';

        public function __construct()
        {
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $imgQueries = array();
            $priceQueries = array();

            for ($i=1; $i<=15; $i++)
            {
                $itemId = '"result_' . $i . '"';

                array_push($titleQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div/a/h2');

                array_push($linkQueries, 
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div/a/attribute::href');

                array_push($authorQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div[2]/span[2]/a/text()');

                array_push($imgQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div/div/div/a/img/attribute::src');

                array_push($priceQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div[2]/div/div/*[contains(.,"EUR")]');
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
             return $this->bookScraper->getBooks($this->domain, $this->queryUrl, $keyword, '');
        }

        public function getQueries(): array
        {
            return $this->queries;
        }
    }
