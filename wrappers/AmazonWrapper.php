<?php

namespace wrappers;

require 'BookScraper.php';

use \wrappers\BookScraper;

class AmazonWrapper
{
    // variables
    private $bookScraper;
    private $queries;
    private $domain = 'https://www.amazon.it';
    private $queryUrl = '/gp/aw/s/?rh=n%3A411663031%2Cp_n_binding_browse-bin%3A1462592031&keywords=';

    public function __construct()
    {
        $this->queries = array(
            'links' => '//ul[@id="resultItems"]/li[1]/a/attribute::href',
            'title' => '//span[@id="ebooksTitle"]/text()',
            'author' => '//div[@id="bylineInfo"]/span/a/text()',
            'price' => '//div[@id="ebooksPrice_feature_div"]/div/div[2]/div[2]/span/text()',
            'editor' => '//div[@id="detailBullets_feature_div"]/div/ul/li[2]/span/span[2]/text()',
            'image' => '//img[@id="ebooksImgBlkFront"]/attribute::src',
            'link' => '//div[@id="swf-sheet-copy"]/a[@id="swf-sheet-network-link"]/attribute::href'
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
