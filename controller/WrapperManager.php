<?php
    namespace controller;

    require './wrappers/AmazonWrapper.php';
    require './wrappers/KoboWrapper.php';
    require './wrappers/GoogleWrapper.php';
    require './wrappers/ReviewWrapper.php';
    require './wrappers/AudibleWrapper.php';
    require './wrappers/NarratoreWrapper.php';

    use \wrappers\GoogleWrapper;
    use \wrappers\AmazonWrapper;
    use \wrappers\KoboWrapper;
    use \wrappers\ReviewWrapper;
    use \wrappers\AudibleWrapper;
    use \wrappers\NarratoreWrapper;

    class WrapperManager {
        private $amazonWrapper;
        private $googleWrapper;
        private $koboWrapper;
        private $reviewWrapper;
        private $audibleWrapper;
        private $ilnarratoreWrapper;

        public function __construct()
        {
            $this->amazonWrapper = new AmazonWrapper;
            $this->googleWrapper = new GoogleWrapper;
            $this->koboWrapper = new KoboWrapper;
            $this->reviewWrapper = new ReviewWrapper;
            $this->audibleWrapper = new AudibleWrapper;
            $this->ilnarratoreWrapper = new NarratoreWrapper;
        }

        public function getBooks($keyword): array
        {
            $googleBooks = array();

            try
            {
                $googleBooks = $this->googleWrapper->getBooks($keyword);
            }
            catch (\Exception $e)
            {
                error_log($e->getMessage());
            }

            $books = array_merge(
                $this->amazonWrapper->getBooks($keyword),
                $googleBooks,
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
            $books = array_merge(
                $this->audibleWrapper->getBooks($keyword),
                $this->ilnarratoreWrapper->getBooks($keyword)
            );

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
            $books = array_merge(
                $this->audibleWrapper->getBooks('', true),
                $this->ilnarratoreWrapper->getNewBooks()
            );

            return $books;
        }

    }
