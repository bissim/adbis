<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';

    require './util/ErrorHandler.php';

    require './controller/WrapperManager.php';
    require './controller/DAOManager.php';

    class Mediator {

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
            switch ($table)
            {
                case 'book':
                    try
                    {
                        $result = $this->jsonEncodeBooks($search, $keyword);
                    }
                    catch (\Throwable $th)
                    {
                        throw $th;
                    }
                    break;
                case 'review':
                    try
                    {
                        $result = $this->jsonEncodeReviews($search, $keyword);
                    }
                    catch (\Throwable $th)
                    {
                        throw $th;
                    }
                    break;
                    case 'join':
                    try
                    {
                        $result = $this->jsonEncodeBoth($search, $keyword);
                    }
                    catch (\Throwable $th)
                    {
                        throw $th;
                    }
                    break;                default:
                    throw new \Exception("unknown table $table.");
                    break;
            }

            return $result;
        }

        private function jsonEncodeBooks(string $search, string $keyword): string
        {
            $daoMng = new DAOManager();
            $books = $daoMng->getBooks($search, $keyword);
            if (empty($books))
            {
                $wrapperMng = new WrapperManager();
                $daoMng->addBooks($wrapperMng->getBooks($keyword));
                $books = $daoMng->getBooks($search, $keyword);
            }

            return json_encode($books);
        }

        private function jsonEncodeReviews(string $search, string $keyword): string
        {
            $daoMng = new DAOManager();
            $reviews = $daoMng->getReviews($search, $keyword);
            if (empty($reviews))
            {
                $wrapperMng = new WrapperManager();
                $daoMng->addReviews($wrapperMng->getReviews($keyword));
                $reviews = $daoMng->getReviews($search, $keyword);
            }

            return json_encode($reviews);
        }

        private function jsonEncodeBoth(string $search, string $keyword): string
        {
            $daoMng = new DAOManager();
            $books = $daoMng->getBooks($search, $keyword);
            $reviews = $daoMng->getReviews($search, $keyword);
            
            if (empty($books))
            {
                $wrapperMng = new WrapperManager();
                $daoMng->addBooks($wrapperMng->getBooks($keyword));
                $books = $daoMng->getBooks($search, $keyword);
            }
            if (empty($reviews))
            
            {
                $wrapperMng = new WrapperManager();
                $daoMng->addReviews($wrapperMng->getReviews($keyword));
                $reviews = $daoMng->getReviews($search, $keyword);
            }

            $booksFound = array();
            $booksFound['reviews'] = array();

            foreach ($books as $book)
            {
                array_push($booksFound, $book);
                foreach ($reviews as $review)
                if ($book->getTitle === $review->getTitle)
                {
                    array_push($booksFound['reviews'], $review);
                }
            }
            
            return json_encode($booksFound);
        }


}