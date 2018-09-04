<?php

namespace wrappers;

require 'BookScraper.php';

use \wrappers\BookScraper;

class AmazonWrapper
{
    // variables
    private $bookScraper;
    private $queries;
    private $baseMobileUrl = 'https://www.amazon.it/gp/aw/s/?rh=p_n_binding_browse-bin%3A1462592031&keywords=';
    private $baseUrl = 'https://www.amazon.it/s/?url=node%3D827182031&field-keywords=';

    public function __construct()
    {
        $this->queries = array(
            'links' => '/html/body/form[3]/a/attribute::href',
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
         return $this->bookScraper->getBooks($this->baseMobileUrl, $keyword, '');
    }

    public function getQueries(): array
    {
        return $this->queries;
    }
}
