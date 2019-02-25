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
        // ?keywords=harry+potter&ref=a_hp_t1_header_search';
        private $queryNewsUrl = 'https://www.audible.it/?source_code=OMDtmSearch0511160001&msclkid=e67212ba5f161e6758ed4efd5dfa7a17';

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
                $itemId = '"result_' . $i . '"';

                array_push($titleQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/h3/a/text()');

                array_push($linkQueries, 
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li/h3/a/attribute::href');

                array_push($authorQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li[2]/span/a/text()');

                array_push($imgQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/div/div/a/img/attribute::src');

                array_push($voiceQueries,
                '//div[@id="center-3"]/div/span/ul/li[' . $i . ']/div/div/div/div/div/div/span/ul/li[3]/span/a/text()');
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
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $authorAltQueries = array();
            $imgQueries = array();
            $voiceQueries = array();

            for ($i=1; $i<=6; $i++)
            {
                array_push($titleQueries,
                '//div[@class="bc-tab-content"]/div/div[1]/div[' . $i . ']/div/div/div/attribute::aria-label');
                array_push($titleQueries,
                '//div[@class="bc-tab-content"]/div/div[2]/div[' . $i . ']/div/div/div/attribute::aria-label');

                array_push($linkQueries, 
                '//div[@class="bc-tab-content"]/div/div[1]/div[' . $i . ']/div/div/div/div/div/a/attribute::href');
                array_push($linkQueries, 
                '//div[@class="bc-tab-content"]/div/div[2]/div[' . $i . ']/div/div/div/div/div/a/attribute::href');

                array_push($authorQueries,
                '//div[@class="bc-popover-container"]/div[' . $i . ']/div/span[1]/ul/li[3]/text()');
                array_push($authorQueries,
                '//div[@class="bc-popover-container"]/div[' . $i . ']/div/span[1]/ul/li[3]/text()');

                // some audiobooks don't have a subtitle
                array_push(
                    $authorAltQueries,
                    '//div[@class="bc-popover-container"]/div[' . $i . ']/div/span[1]/ul/li[2]/text()'
                );
                array_push(
                    $authorAltQueries,
                    '//div[@class="bc-popover-container"]/div[' . $i . ']/div/span[1]/ul/li[2]/text()'
                );

                array_push($imgQueries,
                '//div[@class="bc-tab-content"]/div/div[1]/div[' . $i . ']/div/div/div/div/div/a/img/attribute::src');
                array_push($imgQueries,
                '//div[@class="bc-tab-content"]/div/div[2]/div[' . $i . ']/div/div/div/div/div/a/img/attribute::src');

                array_push($voiceQueries,
                '//div[@class="bc-tab-content"]/div/div[1]/div[' . $i . ']/div/div[@class="bc-section"]/a/text()');
                array_push($voiceQueries,
                '//div[@class="bc-tab-content"]/div/div[2]/div[' . $i . ']/div/div[@class="bc-section"]/a/text()');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['authorAltQueries'] = $authorAltQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['voiceQueries'] = $voiceQueries;

            return $queries;
        }        

        public function getBooks(String $keyword, $new = false): array
        {
            $this->audioBookScraper->setQueries($this->queries);
            $books = $this->audioBookScraper->getBooks(
                $this->domain,
                $this->queryUrl,
                $keyword,
                '',
                $new
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
                true
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
