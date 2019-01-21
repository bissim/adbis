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
                        $result = $this->jsonEncodeBooks($search, $keyword);
                        break;
                    }
                    case 'review':
                    {
                        $result = $this->jsonEncodeReviews($search, $keyword);
                        break;
                    }
                    case 'join':
                    {
                        $result = $this->jsonEncodeBoth($search, $keyword);
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

            return $result;
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
        private function jsonEncodeBooks(string $search, string $keyword): string
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
            return json_encode($books);
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return string
         * @throws \Exception
         */
        private function jsonEncodeReviews(string $search, string $keyword): string
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
            return json_encode($reviews);
        }

        /**
         * @param string $search
         * @param string $keyword
         *
         * @return string
         * @throws \Exception
         */
        private function jsonEncodeBoth(string $search, string $keyword): string
        {
            $dbMng = new DBManager;
            // check in db first
            $books = $dbMng->getAllBooks();
            $reviews = $dbMng->getAllReviews();

            if (empty($books))
            {
                // scrape books from sources
                $wrapperMng = new WrapperManager;
                $dbMng->addBooks($wrapperMng->getBooks($keyword));
                $books = $dbMng->getAllBooks();
            }

            if (empty($reviews))
            {
                // scrape reviews from source
                $wrapperMng = new WrapperManager;
                $dbMng->addReviews($wrapperMng->getReviews($keyword));
                $reviews = $dbMng->getAllReviews();
            }

            $items = array();

            foreach ($books as $book) // TODO Y U ARRAY
            {
                $reviewOfBook = NULL;

                foreach ($reviews as $review)
                {
                    if ($this->isAssociate($book, $review, $search))
                    {
                        $reviewOfBook = $review;
                    }
                }
                $item = array($book, $reviewOfBook);
                array_push($items, $item); 
            }

            return json_encode($items);
        }

        private function isAssociate($book, $review, $search): bool
        {
            switch ($search)
            {
                case 'title': return (strtolower($book['title']) === strtolower($review['title']));
                case 'author': return (strtolower($book['author']) === strtolower($review['author'])
                                && strtolower($book['title']) === strtolower($review['title']));
            }
        }

    }