<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';

    require_once './util/ErrorHandler.php';

    require './controller/WrapperManager.php';
    require './controller/DAOManager.php';

    use \controller\DAOManager;
    use \controller\WrapperManager;

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
            $daoMng = new DAOManager;
            $books = $daoMng->getNewBooks();
            if (empty($books))
            {
                $wrapperMng = new WrapperManager;
                $daoMng->addBooks($wrapperMng->getNewBooks());
                $books = $daoMng->getNewBooks();
            }
            // return json_encode($books);
            return $books;
        }

        private function getNewReviews(): array
        {
            $daoMng = new DAOManager;
            $reviews = $daoMng->getNewReviews();
            if (empty($reviews))
            {
                $wrapperMng = new WrapperManager;
                $daoMng->addReviews($wrapperMng->getNewReviews());
                $reviews = $daoMng->getNewReviews();
            }            
            // return json_encode($reviews);
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
            $daoMng = new DAOManager;
            $books = $daoMng->getBooks($search, $keyword);
            if (empty($books))
            {
                $wrapperMng = new WrapperManager;
                $daoMng->addBooks($wrapperMng->getBooks($keyword));
                $books = $daoMng->getBooks($search, $keyword);
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
            $daoMng = new DAOManager;
            $reviews = $daoMng->getReviews($search, $keyword);
            if (empty($reviews))
            {
                $wrapperMng = new WrapperManager;
                $daoMng->addReviews($wrapperMng->getReviews($keyword));
                $reviews = $daoMng->getReviews($search, $keyword);
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
            $daoMng = new DAOManager;
            // check in db first
            $books = $daoMng->getBooks($search, $keyword);
            $reviews = $daoMng->getReviews($search, $keyword);

            if (empty($books))
            {
                // scrape books from sources
                $wrapperMng = new WrapperManager;
                $daoMng->addBooks($wrapperMng->getBooks($keyword));
                $books = $daoMng->getBooks($search, $keyword);
            }

            if (empty($reviews))
            {
                // scrape reviews from source
                $wrapperMng = new WrapperManager;
                $daoMng->addReviews($wrapperMng->getReviews($keyword));
                $reviews = $daoMng->getReviews($search, $keyword);
            }

            $items = array();

            foreach ($books as $book) // TODO Y U ARRAY
            {
                $reviewOfBook = NULL;

                foreach ($reviews as $review)
                {
                    // if (strtolower($book[$search]) === strtolower($review[$search]) &&
                    //     strtolower($book['title']) === strtolower($review['title']))
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