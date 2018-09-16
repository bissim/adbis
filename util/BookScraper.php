<?php
    namespace util;

    require_once './model/Book.php';

    use \model\Book;
    use \DOMDocument;
    use \DOMXPath;

    class BookScraper
    {

        private $queries;

        public function getQueries(): array
        {
            return $this->queries;
        }

        public function setQueries(array $queries)
        {
            $this->queries = $queries;
        }

        public function getBooks(string $domain, string $queryUrl, string $keyword, string $urlSuffix): array
        {
            // create search URL
            $url = $domain . $queryUrl;
            $keyword = str_replace(' ', '+', strtolower(trim($keyword)));
            $urlSearch = $url . $keyword;
            $urlSuffix = trim($urlSuffix);
            if (!empty($urlSuffix))
            {
                $urlSearch .= $urlSuffix;
            }

            $books = $this->searchBooks($urlSearch);

            return $books;
        }

        private function getWebPage(string $url) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,   // return web page
                CURLOPT_HEADER         => false,  // don't return headers
                CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
                CURLOPT_ENCODING       => '',     // handle compressed
                CURLOPT_USERAGENT      => 'adbis', // name of client
                CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                CURLOPT_TIMEOUT        => 120,    // time-out on response
            );
        
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $content  = curl_exec($ch);
            curl_close($ch);
            return $content;
        }

        private function createDOMXPath(string $url): DOMXPath
        {
            $page = $this->getWebPage($url);
            // $page = file_get_contents($url);
            // create DOM
            $dom = new DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($page);
            libxml_clear_errors();

            // create XPath from DOM
            $xpath = new DOMXPath($dom);

            return $xpath;
        }

        private function searchBooks(string $queryUrl)
        {
            $xpath = $this->createDOMXPath($queryUrl);

            $booksFound = array();

            $entriesTitle = $xpath->query($this->queries['title']);
            $entriesAuthor = $xpath->query($this->queries['author']);
            $entriesPrice = $xpath->query($this->queries['price']);
            $entriesImage = $xpath->query($this->queries['image']);
            $entriesLink = $xpath->query($this->queries['link']);
            
            $length = $entriesAuthor->length;

            for ($i=0; $i<$length; $i++)
            {
                $book = new Book(
                    $this->checkEmpty($entriesTitle[$i]->nodeValue),
                    $this->checkEmpty($entriesAuthor[$i]->nodeValue),
                    $this->checkFloat($entriesPrice[$i]->nodeValue),
                    $this->checkEmpty($entriesImage[$i]->nodeValue),
                    $this->checkEmpty($entriesLink[$i]->nodeValue)
                );
                array_push($booksFound,$book);
            }

            return $booksFound;
        }

        private function checkEmpty($value)
        {
            return empty($value) ? '' : $value;
        }

        private function checkFloat($value)
        {
            $value = preg_replace('/[^0-9,.]/', '', $value);
            $value = str_replace(',', '.', $value);
            return empty($value) ? 0.0 : (float) \floatval($value);
        }
    }
