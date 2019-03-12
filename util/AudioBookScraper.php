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

                // second element of the list may be the subtitle
                // so check whether it contains "Di:" substring or not
                $authorList = $xpath->query($this->queries['authorQueries'][$i]);
                // author is a DOMNodeList of two DOMNode
                $author = $this->nodeExtractor($authorList);
                // fix UTF-8 encoding for author
                $author = Encoding::fixUTF8(trim($author));

//                error_log("Author for '$title' is $author"); // TODO remove

                // third element of the list may be the author
                // so check whether it contains "Letto da:" substring or not
                $voice = $xpath->query($this->queries['voiceQueries'][$i]);
                $voice = $this->nodeExtractor($voice);
                // fix UTF-8 encoding for voice
                $voice = Encoding::fixUTF8(trim($voice));

                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $image = $this->nodeExtractor($image);

                $link = $xpath->query($this->queries['linkQueries'][$i]);
                $link = $this->nodeExtractor($link);

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

        private function stringifyNodeList(DOMNodeList $list): string
        {
            $stringList = "";
            for ($index = 0; $index < $list->length; $index++)
            {
                $stringItem = trim($list->item($index)->nodeValue);
                error_log("Add $stringItem to $stringList...");
                $stringList .= $stringItem;
            }

            return $stringList;
        }
    }
