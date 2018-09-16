<?php
    namespace wrappers;

    require_once './util/BookScraper.php';

    use \util\BookScraper;

    class AmazonWrapper
    {
        // variables
        private $bookScraper;
        private $queries;
        // private $domain = 'https://www.amazon.it';
        private $domain = '';
        // private $queryUrl = '/gp/aw/s/?rh=n%3A411663031%2Cp_n_binding_browse-bin%3A1462592031&keywords=';
        private $queryUrl = 'https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=';

        public function __construct()
        {
            $this->queries = array(
                // 'links' => '//ul[@id="resultItems"]/li/a/attribute::href',
                'link' => '//div[@class="a-fixed-left-grid-col a-col-right"]/div/div/a/attribute::href',
                'title' => '//div[@class="a-fixed-left-grid-col a-col-right"]/div/div/a/h2/text()',
                'author' => '//div[@class="a-row a-spacing-small"]/div[2]/span[2]/a/text()',
                'price' => '//div[@class="s-item-container"]/div/div/div/div/div/div/a/span[2]/text()',
                // 'editor' => '//div[@id="detailBullets_feature_div"]/div/ul/li[2]/span/span[2]/text()',
                'image' => '//div[@class="s-item-container"]/div/div/div/div/div/a/img/attribute::src',
            );
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
