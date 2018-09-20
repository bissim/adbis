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

            $length = count($this->queries['titleQueries']);

            for ($i=0; $i < $length; $i++)
            {
                $title = $xpath->query($this->queries['titleQueries'][$i]);
                $author = $xpath->query($this->queries['authorQueries'][$i]);
                $price = $xpath->query($this->queries['priceQueries'][$i]);
                $image = $xpath->query($this->queries['imgQueries'][$i]);
                $link = $xpath->query($this->queries['linkQueries'][$i]);

                $book = new Book(
                    $this->checkEmpty($title[0]->nodeValue),
                    $this->checkEmpty($author[0]->nodeValue),
                    $this->checkFloat($price[0]->nodeValue),
                    $this->checkEmpty($image[0]->nodeValue),
                    $this->checkEmpty($link[0]->nodeValue)
                );

                array_push($booksFound, $book);
            }
            
            return $booksFound;
        }

        private function checkEmpty($value)
        {
            return empty($value) ? '' : $value;
        }
        private function checkFloat($value)
        {
            if (empty($value)) return 0.0;
            $value = preg_replace('/[^0-9,.]/', '', $value);
            $value = str_replace(',', '.', $value);
            return (float) \floatval($value);
        }
}
