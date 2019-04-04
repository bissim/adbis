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

            $commonXPath = "/html/body/div[@id=\"search\"]/div/div[@class=\"span9 main_content\"]/div/div";

            for ($i=1; $i<=8; $i++)
            {
                array_push(
                    $titleQueries,
                    "$commonXPath/div[$i]/div/div/h4/a/text()"
                );

                array_push(
                    $linkQueries,
                    "$commonXPath/div[$i]/div/div/h4/a/attribute::href"
                );

                array_push(
                    $authorQueries,
                    "$commonXPath/div[$i]/div/div/h4/a/text()"
                );

                array_push(
                    $priceQueries,
                    "$commonXPath/div[$i]/div/div/div/div[@class=\"minh4\"]/div/h4"
                );

                array_push(
                    $imgQueries,
                    "$commonXPath/div[$i]/div/div/div/a/img/attribute::src"
                );

                array_push(
                    $voiceQueries,
                    "$commonXPath/div[$i]/div/div/div/div[@class=\"minh10\"]/div[1]"
                );
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

            $commonXPath = "/html/body/div[@id=\"homepage\"]/div/div[2]";

            for ($i=3; $i<=10; $i++)
            {
                array_push(
                    $titleQueries,
                    "$commonXPath/div[$i]/div/div/div/h4/a/text()"
                );

                array_push(
                    $linkQueries,
                    "$commonXPath/div[$i]/div/div/div/h4/a/attribute::href"
                );

                array_push(
                    $authorQueries,
                    "$commonXPath/div[$i]/div/div/div/h4/a/text()"
                );

                array_push(
                    $priceQueries,
                    "$commonXPath/div[$i]/div/div/div/div[@class=\"tet-center btn_box_group\"]/div[@class=\"minh4\"]/div/h4"
                );

                array_push(
                    $imgQueries,
                    "$commonXPath/div[$i]/div/div/div/div[@class=\"carousel minh2\"]/a/img/attribute::src"
                );

                array_push(
                    $voiceQueries,
                    "$commonXPath/div[$i]/div/div/div/div[@class=\"tet-center btn_box_group\"]/div[@class=\"minh10\"]/div[1]"
                );
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
                $title = $book->getTitle();
                if ($book->getTitle() !== '')
                {
                    $book->setTitle(
                        trim(
                            substr(
                                strstr($title, "-"),
                                1
                            )
                        )
                    );
                    $book->setAuthor(
                        trim(
                            substr($title, 0, strpos($title, "-"))
                        )
                    );
                    $book->setImg('https://ilnarratore.com/' . $book->getImg());
                    $book->setLink('https://ilnarratore.com/' . $book->getLink());
                    $book->setVoice(
                        trim(
                            str_replace(
                                "Voce narrante: ",
                                "",
                                $book->getVoice()
                            )
                        )
                    );

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
                $title = $book->getTitle();
                if ($book->getTitle() !== '')
                {
                    $book->setTitle(
                        trim(
                            substr( // extract substring from start to needle
                                strstr( // calculate first needle position
                                    $title,
                                    "-"
                                ),
                                1
                            )
                        )
                    );
                    $book->setAuthor(
                        trim(
                            substr(
                                $title,
                                0,
                                strpos($title, "-")
                            )
                        )
                    );
                    $book->setLink('https://ilnarratore.com/' . $book->getLink());
                    $book->setImg('https://ilnarratore.com/' . $book->getImg());
                    $voice = str_replace(
                        "Voce narrante: ",
                        "",
                        $book->getVoice()
                    );
                    $needle = strpos($voice, ",");
                    $book->setVoice(
                        trim(
                            substr(
                                $voice,
                                0,
                                $needle? $needle: strlen($voice)
                            )
                        )
                    );

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
