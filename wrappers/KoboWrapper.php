<?php
    namespace wrappers;

    require_once './util/BookScraper.php';

    use \util\BookScraper;

    class KoboWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        private $queriesNews;
        private $queryUrl = 'https://www.kobo.com/it/it/search?fclanguages=it&Query=';
        private $queryNewsUrl = 'https://www.kobo.com/it/it/list/le-nostre-novita/bffF8FuYXEG3kcdpSnLkjg';

        public function __construct()
        {

            $this->queries = $this->createQueries();
            $this->queriesNews = $this->createQueriesNews();
            
            $this->bookScraper = new BookScraper;
        }

        private function createQueries(): array
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

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['priceQueries'] = $priceQueries;

            return $queries;

        }

        private function createQueriesNews(): array
        {
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $imgQueries = array();
            $priceQueries = array();

            for ($i=1; $i<=12; $i++)
            {
                array_push($titleQueries,
                '//ul/li[' . $i . ']/div/div[2]/p[1]/a/text()');

                array_push($linkQueries, 
                '//ul/li[' . $i . ']/div/div[2]/p[1]/a/attribute::href');

                array_push($authorQueries,
                '//ul/li[' . $i . ']/div/div[2]/p/span[@class="visible-contributors"]');

                array_push($imgQueries,
                '//ul/li[' . $i . ']/div/div[1]/div/a/div/img/attribute::src');

                array_push($priceQueries,
                '//ul/li[' . $i . ']/div/div[2]/p[@class="product-field price"]');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['priceQueries'] = $priceQueries;

            return $queries;

        }


        public function getBooks(String $keyword): array
        {
            $this->bookScraper->setQueries($this->queries);
            $books = $this->bookScraper->getBooks(
                '',
                $this->queryUrl,
                $keyword,
                '&fclanguages=it',
                false
            );
            foreach ($books as $book)
            {
                $book->setSource('kobo');
            }
            return $books;
        }

        public function getNewBooks(): array
        {
            $this->bookScraper->setQueries($this->queriesNews);
            return $this->bookScraper->getBooks(
                '',
                $this->queryNewsUrl,
                '',
                '',
                true
            );
        }

        public function getQueries(): array
        {
            // return $this->queries;
            return $this->bookScraper->getQueries();
        }
    }
