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
            string $keyword
        ): string
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
            $books = $this->dbMng->getNewBooks();
            if (!$this->isVarious($books))
            {
               $books = $this->wrapperMng->getNewBooks();
                $this->dbMng->addBooks($books);
            }

            shuffle($books);
            return $books;
        }

        /**
         * @deprecated
         * Retrieve records from review table whose
         * <pre>is_recent</pre> attribute is set to <pre>1</pre>.
         *
         * @return array - Set of new reviews.
         */
        private function getNewReviews(): array
        {
            $reviews = $this->dbMng->getNewReviews();
            if (empty($reviews))
            {
                $reviews = $this->wrapperMng->getNewReviews();
                $this->dbMng->addReviews($reviews);
            }
            return $reviews;
        }

        /**
         * Retrieve records from audiobook table whose
         * <pre>is_recent</pre> attribute is set to <pre>1</pre>.
         *
         * @return array - Set of new audiobooks.
         */
        private function getNewAuBooks(): array
        {
            $auBooks = $this->dbMng->getNewAudioBooks();
            if (!$this->isVarious($auBooks))
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
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle()))
                    || ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor()))
                )
                {
                    array_push($books, $book);
                }

            $newBooks = array();

            if (!$this->isVarious($books))
            {
                $scrapedBooks = $this->wrapperMng->getBooks($keyword);
                $limit = count($books);

                foreach ($scrapedBooks as $book)
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle()))
                    || ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor()))
                )
                {
                    $flag = true;
                    for ($i=0; $i<$limit; $i++)
                        if ($book->equals($books[$i]))
                            $flag = false;
                    if($flag)
                        array_push($newBooks, $book);
                }
                $this->dbMng->addBooks($newBooks);
            }

            shuffle($books);
            return array_merge($books,$newBooks);
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
            $cachedReviews = $this->dbMng->getAllReviews();

            foreach ($cachedReviews as $review)
                if (
                    ($search === 'title' && $this->comp->compare($keyword,$review->getTitle())) ||
                    ($search === 'author' && $this->comp->compare($keyword,$review->getAuthor())) ||
                    $search === 'voice'
                )
                {
                    array_push($reviews, $review);
                }

            if (empty($reviews))
            {
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
            }

            shuffle($reviews);
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
        private function getAudioBooks(string $search, string $keyword): array
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

            $newBooks = array();

            if (!$this->isVariousAudio($books, $search))
            {
                $scrapedAudioBooks = $this->wrapperMng->getAudioBooks($keyword);
                $limit = count($books);
                
                foreach ($scrapedAudioBooks as $book)
                if (
                    ($search === 'title' && $this->comp->compare($keyword, $book->getTitle())) ||
                    ($search === 'author' && $this->comp->compare($keyword, $book->getAuthor())) ||
                    ($search === 'voice' && $this->comp->compare($keyword, $book->getVoice()))
                )
                {
                    $flag = true;
                    for ($i=0; $i<$limit; $i++)
                        if ($book->equals($books[$i]))
                            $flag = false;
                    if($flag)
                        array_push($newBooks, $book);
                }
                $this->dbMng->addAudioBooks($newBooks);
            }

            return array_merge($books,$newBooks);
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
        private function getBoth(string $search, string $keyword): array
        {
            $books = $this->getBooks($search, $keyword);
            $reviews = $this->getReviews($search, $keyword);

            $items = array();

            foreach ($books as $book) // TODO Y U ARRAY
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
        private function getEither(string $search, string $keyword): array
        {
            $audiobooks = $this->getAudioBooks($search, $keyword);
            $reviews = $this->getReviews($search, $keyword);

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

        /**
         * Check whether input book array has records with different titles,
         * i.e. is various.
         *
         * @param array $books - The set to check for variety
         *
         * @return bool - true if set is various, false otherwise
         */
        private function isVarious(array $books): bool
        {
            $numBooks = count($books);

            for ($i = 0; $i < $numBooks; $i++)
            {
                for ($j = $i; $j < $numBooks; $j++)
                {
                    // skip diagonal of comparison matrix
                    if ($i === $j) continue;

                    if (
                        !$this->comp->compare(
                            $books[$i]->getTitle(),
                            $books[$j]->getTitle()
                        )
                        &&
                        $this->comp->compare(
                            $books[$i]->getAuthor(),
                            $books[$j]->getAuthor()
                        )
                    )
                    {
                        // we found two books with unsimilar title
                        return true;
                    }
                }
            }

            // we compared all books in array
            // didn't find any mismatch
            return false;
        }

        private function isVariousAudio(array $books, string $search): bool
        {
            $numBooks = count($books);

            for ($i = 0; $i < $numBooks; $i++)
            {
                for ($j = $i; $j < $numBooks; $j++)
                {
                    // skip diagonal of comparison matrix
                    if ($i === $j) continue;

                    if (
                            ($search==="author" && !$this->comp->compare(
                            $books[$i]->getTitle(),
                            $books[$j]->getTitle()))
                        ||
                            ($search==="voice" && !$this->comp->compare(
                            $books[$i]->getAuthor(),
                            $books[$j]->getAuthor()))
                    )
                    {
                        // we found two books with unsimilar title
                        return true;
                    }
                }
            }

            // we compared all books in array
            // didn't find any mismatch
            return false;
        }

    }
