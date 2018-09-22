<?php
    namespace util;

    require_once './model/Review.php';

    use \model\Review;
    use \DOMDocument;
    use \DOMXPath;

    class ReviewScraper
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

        public function getReviews(string $url, string $keyword, string $urlSuffix, bool $new): array
        {
            // create search URL
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

            // put retrieved links into array
            $links = array();
            foreach ($entries as $entryLink)
            {
                $l = $entryLink->firstChild->nodeValue;
                if (!(substr( $l, 0, 7 ) === "http://") &&
                    !(substr( $l, 0, 8 ) === "https://"))
                    $l = 'https://qlibri.it' . $l;
                array_push($links, $l);

            }

            // retrieve reviews from links
            // as array of Review objects
            $reviews = $this->searchReviews($links, $new);

            return $reviews;
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

        private function searchReviews(array $links, bool $new): array
        {
            $reviewsFound = array();

            // explore every link
            foreach ($links as $link)
            {
                $attributes = $this->extractAttributes($link);

                // create a Review object and put it in array
                $review = new Review(
                    $attributes['title'],
                    $attributes['author'],
                    $attributes['plot'],
                    $attributes['text'],
                    $attributes['avg'],
                    $attributes['style'],
                    $attributes['content'],
                    $attributes['pleasantness'],
                    $new
                );
                array_push($reviewsFound, $review);
            }

            return $reviewsFound;
        }

        private function checkEmpty($entriesValue)
        {
            if ($entriesValue->length > 0)
            {
                $value = $entriesValue[0]->nodeValue;
                return empty($value) ? '' : $value;
            }
            return '';
        }

        private function checkNum($entriesValue)
        {
            if ($entriesValue->length > 0)
            {
                $value = $entriesValue[0]->nodeValue;
                return (float) \floatval($value);
            }
            else return 0;
        }

        private function extractAttributes(string $link): array
        {

            $xpath = $this->createDOMXPath($link);

            $entriesTitle = $xpath->query($this->queries['title']);
            $title = $this->checkEmpty($entriesTitle);

            $entriesAuthor = $xpath->query($this->queries['author']);
            $author = $this->checkEmpty($entriesAuthor);

            $entriesPlot = $xpath->query($this->queries['plot']);
            $plot = $this->checkEmpty($entriesPlot);

            $entriesText = $xpath->query($this->queries['text']);
            $text = $this->checkEmpty($entriesText);

            $entriesAvg = $xpath->query($this->queries['avg']);
            $avg = $this->checkNum($entriesAvg);

            $entriesStyle = $xpath->query($this->queries['style']);
            $style = $this->checkNum($entriesStyle);

            $entriesContent = $xpath->query($this->queries['content']);
            $content = $this->checkNum($entriesContent);

            $entriesPleasantness = $xpath->query($this->queries['pleasantness']);
            $pleasantness = $this->checkNum($entriesPleasantness);

            $attributes = array(
                "title" => $title,
                "author" => $author,
                "plot" => $plot,
                "text" => $text,
                "avg" => $avg,
                "style" => $style,
                "content" => $content,
                "pleasantness" => $pleasantness
            );

            return $attributes;
        }
    }
