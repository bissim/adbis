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

            $commonXPath = "//*[@id=\"search\"]/div[1]/div[2]/div/span[3]/div[1]";

            for ($i=1; $i<=15; $i++)
            {
                array_push($titleQueries,
                "$commonXPath/div[$i]/div/div/div/div[2]/div[2]/div/div[1]/div/div/div[1]/h5/a/span/text()");

                array_push($linkQueries, 
                "$commonXPath/div[$i]/div/div/div/div[2]/div[2]/div/div[1]/div/div/div[1]/h5/a/attribute::href");

                array_push($authorQueries,
                "$commonXPath/div[$i]/div/div/div/div[2]/div[2]/div/div[1]/div/div/div[1]/div//text()"); // TODO extract substring

                array_push($imgQueries,
                "$commonXPath/div[$i]/div/div/div/div[2]/div[1]/div/div/span/a/div/img/attribute::src");

                array_push($priceQueries,
                "$commonXPath/div[$i]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div[1]/div[2]/div/a/span/span[2]/span[1]/text()"); // TODO extract nonzero price
                // alternative price, extract from following
                // $commonXPath/div[$i]/div/div/div/div[2]/div[2]/div/div[2]/div[1]/div/div[2]/div/span
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

            $commonXPath = "//div[@id=\"zg-center-div\"]/ol";

            for ($i=1; $i<=15; $i++)
            {
                array_push($titleQueries,
                "$commonXPath/li[$i]/span/div/span/a/div/text()");

                array_push($linkQueries, 
                "$commonXPath/li[$i]/span/div/span/a/attribute::href");

                array_push($authorQueries,
                "$commonXPath/li[$i]/span/div/span/div[1]");

                array_push($imgQueries,
                "$commonXPath/li[$i]/span/div/span/a/span/div/img/attribute::src");

                array_push($priceQueries,
                "$commonXPath/li[$i]/span/div/span/div/*[contains(.,\"EUR\")]");
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
                $new? '': $keyword,
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
