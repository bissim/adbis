<?php

require '../wrappers/AmazonWrapper.php';
require '../wrappers/KoboWrapper.php';
require '../wrappers/GoogleWrapper.php';

use \wrappers\GoogleWrapper;
use \wrappers\AmazonWrapper;
use \wrappers\KoboWrapper;

class WrapperManager {
    private $amazonWrapper;
    private $googleWrapper;
    private $koboWrapper;

    public function __construct()
    {
        $this->amazonWrapper = new AmazonWrapper();
        $this->googleWrapper = new GoogleWrapper();
        $this->koboWrapper = new KoboWrapper();
    }

    public function getBooks($keyword): array
    {
        $books = array_merge($this->amazonWrapper->getBooks($keyword),
                            $this->googleWrapper->getBooks($keyword),
                            $this->koboWrapper->getBooks($keyword));
        return $books;
    }

}