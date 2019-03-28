<?php
    namespace wrappers;

    require_once './util/AudioBookScraper.php';

    use \util\AudioBookScraper;

    class AudibleWrapper
    {
        // variables
        private $audioBookScraper;
        private $queries;
        private $queriesNews;
        private $domain = '';
        private $queryUrl = 'https://www.audible.it/search?keywords=';
        private $queryNewsUrl = 'https://www.audible.it/search?publication_date=8560986031';

        public function __construct()
        {
            $this->queries = $this->createQueries();
            $this->queriesNews = $this->createQueriesNews();
            $this->audioBookScraper = new AudioBookScraper;
        }

        private function createQueries(): array
        {
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $imgQueries = array();
            $voiceQueries = array();

            for ($i=1; $i<=8; $i++)
            {
                array_push($titleQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/h3/a/text()');

                array_push($linkQueries, 
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/h3/a/attribute::href');

                array_push($authorQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/span[contains(text(),"Di")]/a[1]/text()');

                array_push($imgQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/div/div/a/img/attribute::src');

                array_push($voiceQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/span[contains(text(),"Letto")]/a[1]/text()');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['voiceQueries'] = $voiceQueries;

            return $queries;
        }

        private function createQueriesNews(): array
        {
            return $this->createQueries();
        }        

        public function getBooks(String $keyword, bool $new = false): array
        {
            $this->audioBookScraper->setQueries($this->queries);
            $books = $this->audioBookScraper->getBooks(
                $this->domain,
                $this->queryUrl,
                $new? '': $keyword,
                '',
                $new,
                'audible'
            );
            $effectiveBooks = array();
            foreach ($books as $book)
            {
                if ($book->getTitle() !== '')
                {
                    $book->setLink('https://audible.it' . $book->getLink());
                    array_push($effectiveBooks, $book);
                }
            }

            return $effectiveBooks;
        }

        /**
         * @deprecated
         * Retrieve new Audible books.
         * @return array
         */
        public function getNewBooks(): array
        {
            $this->audioBookScraper->setQueries($this->queriesNews);
            $books = $this->audioBookScraper->getBooks(
                $this->domain,
                $this->queryNewsUrl,
                '',
                '',
                true,
                'audible'
            );
            $effectiveBooks = array();
            foreach ($books as $book)
            {
                if ($book->getTitle() !== '')
                {
                    $book->setLink('https://audible.it' . $book->getLink());
                    array_push($effectiveBooks, $book);
                }
            }

            return $effectiveBooks;
        }

        public function getQueries(): array
        {
            return $this->queries;
        }
    }
