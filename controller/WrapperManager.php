<?php
    namespace controller;

    require './wrappers/AmazonWrapper.php';
    require './wrappers/KoboWrapper.php';
    require './wrappers/GoogleWrapper.php';
    require './wrappers/ReviewWrapper.php';

    use \wrappers\GoogleWrapper;
    use \wrappers\AmazonWrapper;
    use \wrappers\KoboWrapper;
    use \wrappers\ReviewWrapper;

    class WrapperManager {
        private $amazonWrapper;
        private $googleWrapper;
        private $koboWrapper;
        private $reviewWrapper;

        public function __construct()
        {
            $this->amazonWrapper = new AmazonWrapper();
            $this->googleWrapper = new GoogleWrapper();
            $this->koboWrapper = new KoboWrapper();
            $this->reviewWrapper = new ReviewWrapper();
        }

        public function getBooks($keyword): array
        {
            $books = array_merge($this->amazonWrapper->getBooks($keyword),
                                $this->googleWrapper->getBooks($keyword),
                                $this->koboWrapper->getBooks($keyword));
            return $books;
        }

        public function getReviews($keyword): array
        {
            $reviews = $this->reviewWrapper->getReviews($keyword);
            return $reviews;
        }

        
        public function getNewBooks(): array
        {
            $books = array_merge($this->amazonWrapper->getNewBooks(),
                                $this->koboWrapper->getNewBooks());
            return $books;
        }

        public function getNewReviews(): array
        {
            $reviews = $this->reviewWrapper->getNewReviews();
            return $reviews;
        }

    }
