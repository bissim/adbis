<?php
    namespace util;

    require_once './model/Book.php';
    require_once './util/Scraper.php';

    use \model\Book;
    use \DOMDocument;
    use \DOMXPath;
    use \util\Scraper;

    class BookScraper
        extends Scraper
    {
        public function getBooks(
            string $domain,
            string $queryUrl,
            string $keyword,
            string $urlSuffix,
            bool $new
        ): array
        {
            // create search URL
            $url = $domain . $queryUrl;
            $keyword = str_replace(
                ' ',
                '+',
                strtolower(trim($keyword))
            );
            $urlSearch = $url . $keyword;
            $urlSuffix = trim($urlSuffix);
            if (!empty($urlSuffix))
            {
                $urlSearch .= $urlSuffix;
            }

            $books = $this->searchBooks($urlSearch, $new);

            return $books;
        }

        private function searchBooks(string $queryUrl, bool $new) : array
        {
            $xpath = $this->createDOMXPath($queryUrl);

            $booksFound = array();

            $length = count($this->queries['titleQueries']);

            for ($i=0; $i < $length; $i++)
            {
                $title = $xpath->query($this->queries['titleQueries'][$i]);
                $author = $xpath->query($this->queries['authorQueries'][$i]);
                $price = $xpath->query($this->queries['priceQueries'][$i]);
                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $link = $xpath->query($this->queries['linkQueries'][$i]);

                $book = new Book(
                    $this->checkEmpty($title),
                    $this->checkEmpty($author),
                    $this->checkFloat($price),
                    $this->checkEmpty($image),
                    $this->checkEmpty($link),
                    $new
                );

                array_push($booksFound, $book);
            }
            
            return $booksFound;
        }
    }
