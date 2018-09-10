<?php
    namespace util;

    require_once '../vendor/autoload.php';
    require '../model/Book.php';

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

            // create DOMXPath object for XPath queries
            $xpath = $this->createDOMXPath($urlSearch);

            // query results page
            $entries = $xpath->query($this->queries['links']);
//        print_r($entries); // TODO remove

            // print_r($entries[0]->firstChild->nodeValue . "<br>");

            // put retrieved links into array
            $links = array();
//        echo '<br /><br />'; // TODO remove
            foreach ($entries as $entryLink)
//        for ($i = 0; $i < 1; $i++) // TODO test ebook retrieval
            {
                $l = $entryLink->firstChild->nodeValue;
//            $l = $entries[$i]->firstChild->nodeValue;
        //    print_r($domain . $l); // TODO remove
        //    echo '<br /><br />';
                array_push($links, $domain . $l);
            }

            // foreach ($links as $link)
            //     print_r($link . "<br>");

            // retrieve books from links
            // as array of Book objects
            $books = $this->searchBooks($links);

            return $books;
        }

        private function createDOMXPath(string $url): DOMXPath
        {
//        echo "URL to scrape: $url<br />"; // TODO remove

            // load page
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Android 7.0; Mobile; rv:57.0) Gecko/57.0 Firefox/57.0');
            $page = curl_exec($curl);
            if (curl_errno($curl)) // check for execution errors
            {
                echo 'Scraper error ' . curl_errno($curl) . ": " . curl_error($curl);
                exit;
            }
            curl_close($curl);

            // create DOM
            $dom = new DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($page);
            libxml_clear_errors();

            // create XPath from DOM
            $xpath = new DOMXPath($dom);

            return $xpath;
        }

        private function searchBooks(array $links): array
        {
            $booksFound = array();

//        $link = $links[0]; // TODO remove
            // explore every link
            foreach ($links as $link)
            {
                $attributes = $this->extractAttributes($link);

                // create a Book object and put it in array
                $book = new Book(
                    $attributes['title'],
                    $attributes['author'],
                    $attributes['price'],
                    $attributes['image'],
                    $attributes['shortLink'],
                    $attributes['editor']
                );
                array_push($booksFound, $book);
            }

            return $booksFound;
        }

        private function checkEmpty($value)
        {
            return empty($value) ? '' : $value;
        }

        private function extractAttributes(string $link): array
        {
//        echo "<br />execute $query";
            $xpath = $this->createDOMXPath($link);

            $attributes = array();

            $entriesTitle = $xpath->query($this->queries['title']);
            $title = $this->checkEmpty($entriesTitle[0]->nodeValue);

            $entriesAuthor = $xpath->query($this->queries['author']);
            $author = $this->checkEmpty($entriesAuthor[0]->nodeValue);

            $entriesPrice = $xpath->query($this->queries['price']);
            $stringPrice = $entriesPrice[0]->nodeValue;
            $stringPrice = \substr($stringPrice,5);
            $stringPrice = str_replace(',', '.', $stringPrice);
            $price = empty($stringPrice) ? 0.0 : (float) \floatval($stringPrice);

            $entriesEditor = $xpath->query($this->queries['editor']);
            $editor = $this->checkEmpty($entriesEditor[0]->nodeValue);

            $entriesImage = $xpath->query($this->queries['image']);
            $image = $this->checkEmpty($entriesImage[0]->nodeValue);

            $entriesLink = $xpath->query($this->queries['link']);
            $shortLink = $this->checkEmpty($entriesLink[0]->nodeValue);

            $attributes = array(
                "title" => $title,
                "author" => $author,
                "price" => $price,
                "editor" => $editor,
                "image" => $image,
                "shortLink" => $shortLink
            );

        //     if (empty($attribute))
        //     {
        //    echo '<br />empty attribute!<br /><hr />';
        //         return '';
        //     }
//            echo "<br />Attribute: ";
//            print_r($attribute); // TODO remove
//            echo "<hr />";
            return $attributes;
        }
    }
