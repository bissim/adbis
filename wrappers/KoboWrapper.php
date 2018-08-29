<?php

namespace wrappers;

require 'BookScraper.php';

use \wrappers\BookScraper;

class KoboWrapper
{
    // variables
    private $bookScraper;
    private $queries;
    private $baseUrl = 'https://www.kobo.com/it/it/search?Query=';

    public function __construct()
    {
        $this->queries = array(
            'links' => '//div[@class="item-info"]/p[@class="title product-field"]/a/attribute::href',
            'title' => '//div[@class="item-info"]/h1/span/text()',
            'author' => '//div[@class="item-info"]/div/h2/span/span[@class="visible-contributors"]/a[1]/text()',
            'price' => '//div[@class="primary-right-container"]/div/div/div/div/div/span/text()',
            'editor' => '//div[@class="BookItemDetailSecondaryMetadataWidget"]/div/div/div/ul/li/a[@class="description-anchor"]/span/text()',
            'image' => '//div[@class="primary-left-container"]/div/div/div/div/div/img/attribute::src',
        );
        $this->bookScraper = new BookScraper;
        $this->bookScraper->setQueries($this->queries);
    }

    public function getBooks(String $keyword): array
    {
        return $this->bookScraper->getBooks($this->baseUrl, $keyword, '&fclanguages=it');
    }

    public function getQueries(): array
    {
        // return $this->queries;
        return $this->bookScraper->getQueries();
    }
}
