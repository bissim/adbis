<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';
    require_once './util/ErrorHandler.php';
    require './util/StringsComparator.php';
    require './controller/WrapperManager.php';
    require './controller/DBManager.php';
    
    use \controller\WrapperManager;
    use \controller\DBManager;
    use \util\StringsComparator;

    class Mediator
    {
        public function __construct()
        {}

        /**
         * @param string $table
         * @param string $search
         * @param string $keyword
         *
         * @return string
         * @throws \Throwable
         */
        public function retrieve(string $table, string $search, string $keyword): string
        {
            try
            {
                switch ($table)
                {
                    case 'book':
                    {
                        $result = $this->getBooks($search, $keyword);
                        break;
                    }
                    case 'audioBook':
                    {
                        $result = $this->getAudioBooks($search, $keyword);
                        break;
                    }                    
                    case 'review':
                    {
                        $result = $this->getReviews($search, $keyword);
                        break;
                    }
                    case 'join':
                    {
                        $result = $this->getBoth($search, $keyword);
                        break;
                    }
                    case 'join1':
                    {
                        $result = $this->getEither($search, $keyword);
                        break;
                    }                    
                    default:
                    {
                        throw new \Exception("unknown table $table.");
                        break;
                    }
                }
            }
            catch (\Throwable $th)
            {
                throw $th;
            }

            return json_encode($result);
        }

        public function getNewItems(): string
        {
            $res = array();
            $res['ebooks'] = $this->getNewBooks();
            $res['aubooks'] = $this->getNewAuBooks();
            return json_encode($res);
            // return json_encode($this->getNewBooks());
        }

        // Restituisce in formato JSON i nuovi ebook
        private function getNewBooks(): array
        {
            $dbMng = new DBManager;
            $books = $dbMng->getNewBooks();
            if (empty($books)) {
                $wrapperMng = new WrapperManager;
                $books = $wrapperMng->getNewBooks();
                $dbMng->addBooks($books);
            }
            return $books;
        }

        private function getNewReviews(): array
        {
            $dbMng = new DBManager;
            $reviews = $dbMng->getNewReviews();
            if (empty($reviews)) {
                $wrapperMng = new WrapperManager;
                $reviews = $wrapperMng->getNewReviews();
                $dbMng->addReviews($reviews);
            }
            return $reviews;
        }

        private function getNewAuBooks(): array {
            $dbMng = new DBManager;
            $auBooks = $dbMng->getNewAudioBooks();
            if (empty($auBooks)) {
                $wrapperMng = new WrapperManager;
                $auBooks = $wrapperMng->getNewAudioBooks();
                $dbMng->addAudioBooks($auBooks);
            }
            return $auBooks;
        }
        
        /**
         * @param string $search
         * @param string $keyword
         *
         * @return array
         * @throws \Exception
         */
        private function getBooks(string $search, string $keyword): array
        {
            $books = array();
            $dbMng = new DBManager;
            $strComp = new StringsComparator;
            foreach ($dbMng->getAllBooks() as $book)
                if(($search==='title' && $strComp->compare($keyword,$book->getTitle()))
                    || ($search==='author' && $strComp->compare($keyword,$book->getAuthor())))
                    array_push($books,$book);

            if (empty($books))
            {
                $wrapperMng = new WrapperManager;
                foreach($wrapperMng->getBooks($keyword) as $book)
                if(($search==='title' && $strComp->compare($keyword,$book->getTitle()))
                    || ($search==='author' && $strComp->compare($keyword,$book->getAuthor())))
                    array_push($books,$book);
                $dbMng->addBooks($books);
            }
            return $books;
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return array
         * @throws \Exception
         */
        private function getReviews(string $search, string $keyword): array
        {
            $reviews = array();
            $dbMng = new DBManager;
            $strComp = new StringComparator;
            foreach ($dbMng->getAllReviews() as $review)
                if(($search==='title' && $strComp->compare($keyword,$review->getTitle()))
                    || ($search==='author' && $strComp->compare($keyword,$review->getAuthor())))
                    array_push($reviews,$review);

            if (empty($reviews))
            {
                $wrapperMng = new WrapperManager;
                foreach($wrapperMng->getReviews($keyword) as $review)
                    if(($search==='title' && $strComp->compare($keyword,$review->getTitle()))
                        || ($search==='author' && $strComp->compare($keyword,$review->getAuthor())))
                        array_push($reviews,$review);
                $dbMng->addReviews($reviews);
            }
            return $reviews;
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return array
         * @throws \Exception
         */
        private function getAudioBooks(string $search, string $keyword): array
        {
            $books = array();
            $dbMng = new DBManager;
            $strComp = new StringComparator;
            foreach ($dbMng->getAllAudioBooks() as $book)
                if(($search==='title' && $strComp->compare($keyword,$book->getTitle()))
                    || ($search==='author' && $strComp->compare($keyword,$book->getAuthor())))
                    array_push($books,$book);

            if (empty($books))
            {
                $wrapperMng = new WrapperManager;
                foreach($wrapperMng->getAudioBooks($keyword) as $book)
                if(($search==='title' && $strComp->compare($keyword,$book->getTitle()))
                    || ($search==='author' && $strComp->compare($keyword,$book->getAuthor())))
                    array_push($books,$book);
                $dbMng->addAudioBooks($books);
            }
            return $books;
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return array
         * @throws \Exception
         */
        private function getBoth(string $search, string $keyword): array
        {
            $books = $this->getBooks($search, $keyword);
            $reviews = $this->getReviews($search, $keyword);

            $items = array();
            $comp = new StringComparator;

            foreach ($books as $book) // TODO Y U ARRAY
            {
                $reviewOfBook = NULL;

                foreach ($reviews as $review)
                {
                    if($comp->compare($book->getTitle(),$review->getTitle())
                     && $comp->compare($book->getAuthor(),$review->getAuthor()))
                        $reviewOfBook = $review;
                }
                $item = array($book, $reviewOfBook);
                array_push($items, $item); 
            }

            return $items;
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return array
         * @throws \Exception
         */
        private function getEither(string $search, string $keyword): array
        {
            $books = $this->getAudioBooks($search, $keyword);
            $reviews = $this->getReviews($search, $keyword);

            $items = array();
            $comp = new StringComparator;

            foreach ($books as $book) // TODO Y U ARRAY
            {
                $reviewOfBook = NULL;

                foreach ($reviews as $review)
                {
                    if($comp->compare($book->getTitle(),$review->getTitle())
                     && $comp->compare($book->getAuthor(),$review->getAuthor()))
                        $reviewOfBook = $review;
                }
                $item = array($book, $reviewOfBook);
                array_push($items, $item); 
            }

            return $items;
        }        

    }
