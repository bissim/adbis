<?php
    namespace util;

    require_once './model/AudioBook.php';
    require_once './util/Scraper.php';

    use \model\AudioBook;
    use \DOMDocument;
    use \DOMNodeList;
    use \DOMXPath;
    use \util\Scraper;
    use \ForceUTF8\Encoding;

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
                // fix UTF-8 encoding for title
                $title = Encoding::fixUTF8(trim($this->nodeExtractor($title)));
                $authorList = $xpath->query($this->queries['authorQueries'][$i]);
                // author is a DOMNodeList of two DOMNode
                $author = $this->nodeExtractor($authorList);
                if (
                    $author &&
                    stripos("Di:", $author) === FALSE &&
                    $authorList->item(1)
                )
                {
                    $author = $authorList->item(1)->nodeValue;
                }
                // clean author field
                $author = str_replace(
                    'Di:',
                    '',
                    $author
                );
                // fix UTF-8 encoding for author
                $author = Encoding::fixUTF8(trim($author));
                $voice = $xpath->query($this->queries['voiceQueries'][$i]);
                $voice = $this->nodeExtractor($voice);
                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $image = $this->nodeExtractor($image);
                $link = $xpath->query($this->queries['linkQueries'][$i]);
                $link = $this->nodeExtractor($link);

                error_log("Author for '$title' is $author");

                $book = new AudioBook(
                    $title,
                    $author,
                    $voice,
                    $image,
                    $link,
                    $new
                );

                array_push($booksFound, $book);
            }

            return $booksFound;
        }

        private function nodeExtractor(DOMNodeList $node)
        {
            if ($node->item(0))
            {
                return $node->item(0)->nodeValue;
            }

//            error_log("Empty node!");
            return '';
        }

    }
