<?php
    namespace controller;

    require './controller/BookDAO.php';
    require './controller/ReviewDAO.php';

    use \controller\BookDAO;
    use \controller\ReviewDAO;

    class DAOManager
    {
        private $bookDAO;
        private $reviewDAO;

        public function __construct()
        {
            $this->bookDAO = new BookDAO;
            $this->reviewDAO = new ReviewDAO;
        }

        // Ricerca i libri nel database in base all'autore o al titolo
        public function getBooks(string $search, string $keyword): array
        {
            $books = array();

            switch ($search)
            {
                case 'author':
                    $books = $this->bookDAO->retrieveByAuthor($keyword);
                    break;
                case 'title':
                    $books = $this->bookDAO->retrieveByTitle($keyword);
                    break;
                default:
                    user_error(
                        "Unable to search for $search $keyword" .
                        " book, $search not defined."
                    );
                    break;
            }

            return $books;
        }

        // Aggiunge libri nel database
        /**
         * @param array $books
         *
         * @throws \Exception
         */
        public function addBooks(array $books)
        {
            foreach ($books as $book)
            {
                $this->bookDAO->create($book);
            }
        }

        // Ricerca le recensioni nel database in base all'autore o al titolo
        public function getReviews(string $search, string $keyword): array
        {
            $reviews = array();

            switch ($search)
            {
                case 'author':
                    $reviews = $this->reviewDAO->retrieveByAuthor($keyword);
                    break;
                case 'title':
                    $reviews = $this->reviewDAO->retrieveByTitle($keyword);
                    break;
                default:
                    user_error(
                        "Unable to search for $search $keyword" .
                        " review, $search not defined."
                    );
                    break;
            }

            return $reviews;
        }

        // Aggiunge libri nel database
        /**
         * @param array $reviews
         *
         * @throws \Exception
         */
        public function addReviews(array $reviews)
        {
            foreach ($reviews as $review)
            {
                $this->reviewDAO->create($review);
            }
        }

        public function getNewBooks(): array
        {
            return $this->bookDAO->retrieveNew();
        }

        public function getNewReviews(): array
        {
            return $this->reviewDAO->retrieveNew();
        }
}
