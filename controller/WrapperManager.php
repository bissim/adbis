<?php
    namespace controller;

    require './wrappers/AmazonWrapper.php';
    require './wrappers/KoboWrapper.php';
    require './wrappers/GoogleWrapper.php';
    require './wrappers/ReviewWrapper.php';
    require './wrappers/AudibleWrapper.php';

    use \wrappers\GoogleWrapper;
    use \wrappers\AmazonWrapper;
    use \wrappers\KoboWrapper;
    use \wrappers\ReviewWrapper;
    use \wrappers\AudibleWrapper;

    class WrapperManager {
        private $amazonWrapper;
        private $googleWrapper;
        private $koboWrapper;
        private $reviewWrapper;
        private $audibleWrapper;

        public function __construct()
        {
            $this->amazonWrapper = new AmazonWrapper;
            $this->googleWrapper = new GoogleWrapper;
            $this->koboWrapper = new KoboWrapper;
            $this->reviewWrapper = new ReviewWrapper;
            $this->audibleWrapper = new AudibleWrapper;
        }

        public function getBooks($keyword): array
        {
            $books = array_merge(
                $this->amazonWrapper->getBooks($keyword),
                $this->googleWrapper->getBooks($keyword),
                $this->koboWrapper->getBooks($keyword)
            );

            return $books;
        }

        public function getReviews($keyword): array
        {
            $reviews = $this->reviewWrapper->getReviews($keyword);

            return $reviews;
        }

        public function getAudioBooks($keyword): array
        {
            $books = $this->audibleWrapper->getBooks($keyword);

            return $books;
        }
        
        public function getNewBooks(): array
        {
            $books = array_merge(
                $this->amazonWrapper->getNewBooks(),
                $this->koboWrapper->getNewBooks()
            );

            return $books;
        }

        public function getNewReviews(): array
        {
            $reviews = $this->reviewWrapper->getNewReviews();

            return $reviews;
        }

        public function getNewAudioBooks(): array
        {
//            $books = $this->audibleWrapper->getNewBooks();
            $books = $this->audibleWrapper->getBooks('', true);

            return $books;
        }

    }
