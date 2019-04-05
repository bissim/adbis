<?php
    namespace util;

    require_once './model/Book.php';
    require_once './util/Scraper.php';

    use \model\Book;
    use \DOMDocument;
    use \DOMXPath;
    use \util\Scraper;
    use \ForceUTF8\Encoding;

    class BookScraper
        extends Scraper
    {
        public function getBooks(
            string $domain,
            string $queryUrl,
            string $keyword,
            string $urlSuffix,
            string $source,
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

            $books = $this->searchBooks($urlSearch, $source, $new);

            return $books;
        }

        private function searchBooks(string $queryUrl, string $source, bool $new) : array
        {
            $xpath = $this->createDOMXPath($queryUrl);

            $booksFound = array();

            $length = count($this->queries['titleQueries']);

            for ($i=0; $i < $length; $i++)
            {
                $title = $xpath->query($this->queries['titleQueries'][$i]);
                $title = Encoding::fixUTF8(trim($this->checkEmpty($title)));
                $author = $xpath->query($this->queries['authorQueries'][$i]);
                $author = Encoding::fixUTF8(trim($this->checkEmpty($author)));
                $price = $xpath->query($this->queries['priceQueries'][$i]);
                $price = trim($this->checkFloat($price));
                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $image = trim($this->checkEmpty($image));
                $link = $xpath->query($this->queries['linkQueries'][$i]);
                $link = trim($this->checkEmpty($link));

                // fix Amazon attributes
                if ($source === 'amazon')
                {
                    $author = str_replace(' e', '', $author);
                    if ($price == 0.0)
                    {
                        $price = $xpath->query($this->queries['priceAltQueries'][$i]);
                        $price = trim($this->checkFloat($price));
                    }
                    $link = "https://www.amazon.it$link";
                }

                $book = new Book(
                    $title,
                    $author,
                    $price,
                    $image,
                    $link,
                    $source,
                    $new
                );

                array_push($booksFound, $book);
            }
            
            return $booksFound;
        }
    }
