<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';
    require_once './util/ErrorHandler.php';
    require './controller/WrapperManager.php';
    require './controller/DBManager.php';
    require './controller/StringComparator.php';
    
    use \controller\WrapperManager;
    use \controller\DBManager;
    use \controller\StringComparator;

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
            $res['books'] = $this->getNewBooks();
            $res['reviews'] = $this->getNewReviews();
            return json_encode($res);
        }

        // Restituisce in formato JSON i nuovi ebook
        private function getNewBooks(): array
        {
            $dbMng = new DBManager;
            $books = $dbMng->getNewBooks();
            if (empty($books))
            {
                $wrapperMng = new WrapperManager;
                $dbMng->addBooks($wrapperMng->getNewBooks());
                $books = $dbMng->getNewBooks();
            }
            return $books;
        }

        private function getNewReviews(): array
        {
            $dbMng = new DBManager;
            $reviews = $dbMng->getNewReviews();
            if (empty($reviews))
            {
                $wrapperMng = new WrapperManager;
                $dbMng->addReviews($wrapperMng->getNewReviews());
                $reviews = $dbMng->getNewReviews();
            }
            return $reviews;
        }
        
        /**
         * @param string $search
         * @param string $keyword
         *
         * @return string
         * @throws \Exception
         */
        private function getBooks(string $search, string $keyword): array
        {
            $books = array();
            $dbMng = new DBManager;
            $strComp = new StringComparator;
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
         * @return string
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
         * @return string
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

    }