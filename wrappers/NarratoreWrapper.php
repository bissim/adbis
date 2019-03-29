<?php
    namespace wrappers;

    require_once './util/AudioBookScraper.php';

    use \util\AudioBookScraper;

    class NarratoreWrapper
    {
        // variables
        private $audioBookScraper;
        private $queries;
        private $queriesNews;
        private $domain = '';
        private $queryUrl = 'https://www.ilnarratore.com/it/search-results/?action=search&keyword=';
        private $queryNewsUrl = 'https://www.ilnarratore.com/it/';

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
            $priceQueries = array();
            $imgQueries = array();
            $voiceQueries = array();

            for ($i=1; $i<=8; $i++)
            {
                array_push($titleQueries,
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/h4/a/text()');

                array_push($linkQueries, 
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/h4/a/attribute::href');

                array_push($authorQueries,
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/h4/a/text()');

                array_push($priceQueries,
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/div/div[@class="minh4"]/div/h4');

                array_push($imgQueries,
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/div/a/img/attribute::src');

                array_push($voiceQueries,
                '/html/body/div[@id="search"]/div/div[@class="span9 main_content"]/div/div/div[' . $i . ']/div/div/div/div[@class="minh10"]/div[1]');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['priceQueries'] = $priceQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['voiceQueries'] = $voiceQueries;

            return $queries;
        }


        private function createQueriesNews(): array
        {
            $titleQueries = array();
            $linkQueries = array();
            $authorQueries = array();
            $priceQueries = array();
            $imgQueries = array();
            $voiceQueries = array();

            for ($i=3; $i<=10; $i++)
            {
                array_push($titleQueries,
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/h4/a/text()');

                array_push($linkQueries, 
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/h4/a/attribute::href');

                array_push($authorQueries,
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/h4/a/text()');

                array_push($priceQueries,
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/div[@class="tet-center btn_box_group"]/div[@class="minh4"]/div/h4');

                array_push($imgQueries,
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/div[@class="carousel minh2"]/a/img/attribute::src');

                array_push($voiceQueries,
                '/html/body/div[@id="homepage"]/div/div[2]/div[' . $i . ']/div/div/div/div[@class="tet-center btn_box_group"]/div[@class="minh10"]/div[1]');
            }

            $queries = array();
            $queries['titleQueries'] = $titleQueries;
            $queries['linkQueries'] = $linkQueries;
            $queries['authorQueries'] = $authorQueries;
            $queries['priceQueries'] = $priceQueries;
            $queries['imgQueries'] = $imgQueries;
            $queries['voiceQueries'] = $voiceQueries;

            return $queries;
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
                'ilnarratore'
            );
            $effectiveBooks = array();
            foreach ($books as $book)
            {
                $t = $book->getTitle();
                if ($book->getTitle() !== '')
                {
                    $book->setTitle(substr(strstr($t, "-"),1));
                    $book->setAuthor(substr($t, 0, strpos($t, "-")));
                    $book->setImg('https://IlNarratore.com/' . $book->getImg());
                    $book->setLink('https://IlNarratore.com/' . $book->getLink());
                    $book->setVoice(str_replace("Voce narrante: ", "", $book->getVoice()));
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
                'ilnarratore'
            );
            $effectiveBooks = array();
            foreach ($books as $book)
            {
                $t = $book->getTitle();
                if ($book->getTitle() !== '')
                {
                    $book->setTitle(substr(strstr($t, "-"),1));
                    $book->setAuthor(substr($t, 0, strpos($t, "-")));
                    $book->setLink('https://IlNarratore.com/' . $book->getLink());
                    $book->setImg('https://IlNarratore.com/' . $book->getImg());
                    $book->setVoice(str_replace("Voce narrante: ", "", $book->getVoice()));
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
