<?php
    namespace util;

    require_once './model/AudioBook.php';
    require_once './util/Scraper.php';

    use \model\AudioBook;
    use \DOMDocument;
    use \DOMXPath;
    use \util\Scraper;

    class AudioBookScraper
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
                $title = $this->nodeExtractor($title);
                $author = $xpath->query($this->queries['authorQueries'][$i]);
                $author = $this->nodeExtractor($author);
                $valid = false;
                if ($author)
                {
                    $valid = stripos("Di:", $author);
                }
                // check whether author contains 'Di:' substring
                if ($new && $author && !$valid)
                {
                    $author = $xpath->query($this->queries['authorAltQueries'][$i]);
                    $author = $author->item(0)->nodeValue;
                }
                // clean author field
                $author = str_replace(
                    'Di:',
                    '',
                    $author
                );
                $author = trim($author);
                $voice = $xpath->query($this->queries['voiceQueries'][$i]);
                $voice = $this->nodeExtractor($voice);
                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $image = $this->nodeExtractor($image);
                $link = $xpath->query($this->queries['linkQueries'][$i]);
                $link = $this->nodeExtractor($link);

//                var_dump($title);
//                var_dump($author);
//                var_dump($voice);
//                var_dump($image);
//                var_dump($link);

//                $title = $this->checkEmpty($title);
//                $author = $this->checkEmpty($author);
//                $voice = $this->checkEmpty($voice);
//                $image = $this->checkEmpty($image);
//                $link = $this->checkEmpty($link);

//                error_log(
//                    "Title: $title, " .
//                    "Author: $author, " .
//                    "Voice: $voice, " .
//                    "Image: $image, " .
//                    "Link: $link"
//                );

                $book = new AudioBook(
                    $title,
                    $author,
                    $voice,
                    $image,
                    $link,
                    $new
                );

//                error_log("New audiobook created!");

                array_push($booksFound, $book);
            }

            return $booksFound;
        }

        private function nodeExtractor($node)
        {
            if ($node->item(0))
            {
                return $node->item(0)->nodeValue;
            }

            error_log("Empty node!");
            return '';
        }

    }
