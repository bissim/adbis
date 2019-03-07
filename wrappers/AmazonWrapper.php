<?php
    namespace wrappers;

    require_once './util/BookScraper.php';
    require_once './model/Book.php';

    use \util\BookScraper;
    use \model\Book;

    class AmazonWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        private $queriesNews;
        private $domain = '';
        private $queryUrl = 'https://www.amazon.it/s/?url=node%3D827182031&field-keywords=';
        private $queryNewsUrl = 'https://www.amazon.it/gp/new-releases/digital-text/827182031';

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

            for ($i=1; $i<=15; $i++)
            {
                $itemId = '"result_' . $i . '"';

                array_push($titleQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div/a/h2');

                array_push($linkQueries, 
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div/a/attribute::href');

                array_push($authorQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div/div[2]/span[2]//text()');

                array_push($imgQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div/div/div/a/img/attribute::src');

                array_push($priceQueries,
                '//ul/li[@id=' . $itemId . ']/div/div/div/div[2]/div[2]/div[1]/div/a/span[2]/text()');
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

            for ($i=1; $i<=15; $i++)
            {
                array_push($titleQueries,
                '//div[@id="zg-center-div"]/ol/li[' . $i . ']/span/div/span/a/div/text()');

                array_push($linkQueries, 
                '//div[@id="zg-center-div"]/ol/li[' . $i . ']/span/div/span/a/attribute::href');

                array_push($authorQueries,
                '//div[@id="zg-center-div"]/ol/li[' . $i . ']/span/div/span/div[1]');

                array_push($imgQueries,
                '//div[@id="zg-center-div"]/ol/li[' . $i . ']/span/div/span/a/span/div/img/attribute::src');

                array_push($priceQueries,
                '//div[@id="zg-center-div"]/ol/li[' . $i . ']/span/div/span/div/*[contains(.,"EUR")]');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['priceQueries'] = $priceQueries;

            return $queries;
        }        

        public function getBooks(String $keyword, $new = false): array
        {
            $this->bookScraper->setQueries($this->queries);
            $books = $this->bookScraper->getBooks(
                $this->domain,
                $this->queryUrl,
                $keyword,
                '',
                'amazon',
                $new
            );
            $effectiveBooks = array();
            foreach ($books as $book)
            {
                if ($book->getTitle() !== '')
                {
                    if ($new)
                    {
                        $book->setLink('https://amazon.it' . $book->getLink());
                        $book->setRecent(true);
                    }
                    $book->setSource('amazon');
                    array_push($effectiveBooks, $book);
                }
            }

            return $effectiveBooks;
        }

        /**
         * @deprecated
         * Retrieve new Amazon books.
         * @return array
         */
        public function getNewBooks(): array
        {
            $this->bookScraper->setQueries($this->queriesNews);
            $books = $this->bookScraper->getBooks(
                $this->domain,
                $this->queryNewsUrl,
                '',
                '',
                'amazon',
                true
            );
            foreach ($books as $book)
            {
                $book->setLink('https://amazon.it' . $book->getLink());
                $book->setRecent(true);
                $book->setSource('amazon');
            }

            return $books;
        }

        public function getQueries(): array
        {
            return $this->queries;
        }
    }
