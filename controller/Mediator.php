<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';
    require_once './util/ErrorHandler.php';
    require './util/TokensManager.php';
    require './controller/WrapperManager.php';
    require './controller/DBManager.php';
    
    use \controller\WrapperManager;
    use \controller\DBManager;
    use \util\TokensManager;

    /**
     * Class Mediator
     * <br />
     * This is the main class responsible to retrieve books, audiobooks and
     * reviews from sources. It checks whether wanted results are already
     * stored in cache before proceeding into querying wrappers.<br />
     * The main methods, <pre>retrieve(string, string, string)</pre> and
     * <pre>getNewItems()</pre> return a JSON representation of results.
     *
     * @package controller
     */
    class Mediator
    {
        /**
         * @var TokensManager - Tokens comparator
         */
        private $comp;
        /**
         * @var \controller\DBManager - Database manager
         */
        private $dbMng;
        /**
         * @var \controller\WrapperManager - Wrappers manager
         */
        private $wrapperMng;

        /**
         * Mediator constructor.
         * <br />
         * Initialize various manager for token comparison,
         * database and wrappers.
         */
        public function __construct()
        {
            $this->comp = new TokensManager;
            $this->dbMng = new DBManager;
            $this->wrapperMng = new WrapperManager;
        }

        /**
         * Retrieve results for specified keyword
         * according to table, table attribute.
         *
         * @param string $table - The table to search for.
         * @param string $search - The table attribute to search for.
         * @param string $keyword - Keyword for search.
         *
         * @return string - JSON codification of results.
         * @throws \Throwable
         */
        public function retrieve(
            string $table,
            string $search,
            string $keyword,
            string $depth
        ): string
        {
            try
            {
                switch ($table)
                {
                    case 'ebook':
                    {
                        $result = $this->getEBooks($search, $keyword, $depth);
                        break;
                    }
                    case 'audiobook':
                    {
                        $result = $this->getAudioBooks($search, $keyword, $depth);
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

        /**
         * Retrieve records from book and audiobook tables
         * whose <pre>is_recent</pre> attribute is <pre>1</pre>.
         *
         * @return string - JSON codification of results
         */
        public function getNewItems(): string
        {
            $res = array();
            $res['ebooks'] = $this->getNewBooks();
            $res['aubooks'] = $this->getNewAuBooks();

            return json_encode($res);
        }

        /**
         * Retrieve records from book table whose
         * <pre>is_recent</pre> attribute is set to <pre>1</pre>.
         *
         * @return array - Set of new books.
         */
        private function getNewBooks(): array
        {
            try
            {
                $books = $this->dbMng->getNewBooks();
            }
            catch (\Exception $e)
            {
                error_log($e->getMessage());
                $books = array();
            }

            if (empty($books))
            {
               $books = $this->wrapperMng->getNewBooks();
               $this->dbMng->addBooks($books);
            }

            shuffle($books);
            return $books;
        }

        /**
         * Retrieve records from audiobook table whose
         * <pre>is_recent</pre> attribute is set to <pre>1</pre>.
         *
         * @return array - Set of new audiobooks.
         */
        private function getNewAuBooks(): array
        {
            try
            {
                $auBooks = $this->dbMng->getNewAudioBooks();
            }
            catch (\Exception $e)
            {
                error_log($e->getMessage());
                $auBooks = array();
            }

            if (empty($auBooks))
            {
                $auBooks = $this->wrapperMng->getNewAudioBooks();
                $this->dbMng->addAudioBooks($auBooks);
            }

            shuffle($auBooks);
            return $auBooks;
        }

        /**
         * Retrieve books for specified keyword according to specified
         * attribute: it can be <pre>title</pre> or <pre>author</pre>.
         *
         * @param string $search - The search attribute.
         * @param string $keyword - The search keyword.
         *
         * @return array - Set of books.
         * @throws \Exception
         */
        private function getBooks(string $search, string $keyword): array
        {
            $books = array();
            $cacheBooks = $this->dbMng->getAllBooks();

            foreach ($cacheBooks as $book)
            {
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle()))
                    || ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor()))
                )
                {
                    array_push($books, $book);
                }
            }

            shuffle($books);
            return $books;
        }

        /**
         * Retrieve reviews for specified keyword according to specified
         * attribute: it can be <pre>title</pre> or <pre>author</pre> or
         * <pre>voice</pre>.
         *
         * @param string $search - The search attribute.
         * @param string $keyword - The search keyword.
         *
         * @return array - Set of reviews.
         * @throws \Exception
         */
        private function getReviews(string $search, string $keyword): array
        {
            $reviews = array();
            
            $scrapedReviews = $this->wrapperMng->getReviews($keyword);
            foreach ($scrapedReviews as $review)
            if (
                ($search === 'title' && $this->comp->compare($keyword, $review->getTitle())) ||
                ($search === 'author' && $this->comp->compare($keyword, $review->getAuthor())) ||
                $search === 'voice'
                )
                {
                    array_push($reviews, $review);
                }
            $this->dbMng->addReviews($reviews);
            return $reviews;
        }

        /**
         * Retrieve books and related reviews for specified keyword
         * according to specified attribute: it can be <pre>title</pre>
         * or <pre>author</pre> or <pre>voice</pre>.
         *
         * @param string $search - The search attribute.
         * @param string $keyword - The search keyword.
         *
         * @return array - Set of books and related reviews.
         * @throws \Exception
         */
        private function getEBooks(string $search, string $keyword, string $depth): array
        {
            $books = $this->getBooks($search, $keyword);
            $reviews = array();

            if ($depth == 'scraping' || empty($books))
            {
                $newBooks = array();
                $scrapedBooks = $this->wrapperMng->getBooks($keyword);
                $limit = count($books);

                foreach ($scrapedBooks as $book)
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle()))
                    || ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor()))
                )
                {
                    $flag = true;
                    for ($i = 0; $i < $limit; $i++)
                    {
                        if ($book->equals($books[$i]))
                        {
                            $flag = false;
                        }
                    }
                    if ($flag)
                    {
                        array_push($newBooks, $book);
                    }
                }

                $this->dbMng->addBooks($newBooks);
                $books = array_merge($books, $newBooks);
                $reviews = $this->getReviews($search, $keyword);
            }
            else
            {
                $reviews = $this->getCachedReviews($search, $keyword);
            }

            shuffle($books);
            $items = array();

            foreach ($books as $book)
            {
                $reviewOfBook = NULL;

                foreach ($reviews as $review)
                {
                    if (
                        $this->comp->isTokenContained($book->getTitle(), $review->getTitle()) &&
                        $this->comp->isTokenContained($book->getAuthor(), $review->getAuthor())
                    )
                    {
                        $reviewOfBook = $review;
                    }
                }

                $item = array($book, $reviewOfBook);
                array_push($items, $item); 
            }

            return $items;
        }

        private function getCachedReviews(string $search, string $keyword): array
        {
            $allReviews = $this->dbMng->getAllReviews();
            $reviews = array();

            foreach ($allReviews as $review)
                if (
                    ($search === 'title' && $this->comp->compare($keyword,$review->getTitle())) ||
                    ($search === 'author' && $this->comp->compare($keyword,$review->getAuthor())) ||
                    $search === 'voice'
                )
                {
                    array_push($reviews, $review);
                }

            return $reviews;
        }
        
                /**
         * Retrieve audiobooks for specified keyword according to specified
         * attribute: it can be <pre>title</pre> or <pre>author</pre> or
         * <pre>voice</pre>.
         *
         * @param string $search - The search attribute.
         * @param string $keyword - The search keyword.
         *
         * @return array - Set of audiobooks.
         * @throws \Exception
         */
        private function getCachedAudioBooks(string $search, string $keyword): array
        {
            $books = array();
            $cachedAudioBooks = $this->dbMng->getAllAudioBooks();

            foreach ($cachedAudioBooks as $book)
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle())) ||
                    ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor())) ||
                    ($search === 'voice' && $this->comp->compare($keyword, $book->getVoice()))
                )
                {
                    array_push($books, $book);
                }
            
            return $books;
        }


        /**
         * Retrieve audiobooks and related reviews for specified keyword
         * according to specified attribute: it can be <pre>title</pre> or
         * <pre>author</pre> or <pre>voice</pre>.
         *
         * @param string $search - The search attribute.
         * @param string $keyword - The search keyword.
         *
         * @return array - Set of audiobooks and related reviews.
         * @throws \Exception
         */
        private function getAudioBooks(string $search, string $keyword, string $depth): array
        {
            $audiobooks = $this->getCachedAudioBooks($search, $keyword);
            $reviews = array();

            if ($depth == 'scraping' || empty($audiobooks))
            {
                $newBooks = array();
                $scrapedAudioBooks = $this->wrapperMng->getAudioBooks($keyword);
                $limit = count($audiobooks);
                
                foreach ($scrapedAudioBooks as $book)
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle())) ||
                    ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor())) ||
                    ($search === 'voice' && $this->comp->compare($keyword, $book->getVoice()))
                )
                {
                    $flag = true;
                    for ($i=0; $i<$limit; $i++)
                        if ($book->equals($audiobooks[$i]))
                            $flag = false;
                    if ($flag)
                        array_push($newBooks, $book);
                }
                $this->dbMng->addAudioBooks($newBooks);
                $audiobooks = array_merge($audiobooks, $newBooks);
                $reviews = $this->getReviews($search,$keyword);
            }
            else
            {
                $reviews = $this->getCachedReviews($search,$keyword);
            }

            shuffle($audiobooks);
            $items = array();

            foreach ($audiobooks as $audiobook) // TODO Y U ARRAY
            {
                $audiobookReview = NULL;

                foreach ($reviews as $review)
                {
                    if (
                        $this->comp->isTokenContained($audiobook->getTitle(), $review->getTitle()) &&
                        $this->comp->isTokenContained($audiobook->getAuthor(), $review->getAuthor())
                    )
                    {
                        $audiobookReview = $review;
                    }
                }

                $item = array($audiobook, $audiobookReview);
                array_push($items, $item); 
            }

            return $items;
        }

    }
