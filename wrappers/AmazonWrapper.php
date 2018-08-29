<?php

namespace wrappers;

require 'BookScraper.php';

use \wrappers\BookScraper;

class AmazonWrapper
{
    // variables
    private $bookScraper;
    private $queries;
    private $baseUrl = 'https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=';

    public function __construct()
    {
        $this->queries = array(
            'links' => '//div[@class="a-row a-spacing-small"]/div[@class="a-row a-spacing-none"]/a/attribute::href',
            'title' => '//div[@id="centerCol"]/div/div/h1/span[@id="ebooksProductTitle"]/text()',
            'author' => '//div[@id="centerCol"]/div[@id="booksTitle"]/div[@id="bylineInfo"]/span/span[@class="a-declarative"]/a[1]/text()',
            'price' => '//table[@class="a-lineitem a-spacing-micro"]//tr[@class="kindle-price"]/td[2]',
            'editor' => '//div[@id="detail_bullets_id"]/table//tr/td[@class="bucket"]/div/ul/li[4]/text()',
            'image' => '//div[@id="leftCol"]/div[1]/div/div[2]/div/div/div/div/img/attribute::src',
        );
        $this->bookScraper = new BookScraper;
        $this->bookScraper->setQueries($this->queries);
    }

    public function getBooks(String $keyword): array
    {
        return $this->bookScraper->getBooks($this->baseUrl, $keyword, '');
    }

    public function getQueries(): array
    {
        // return $this->queries;
        return $this->bookScraper->getQueries();
    }
}
