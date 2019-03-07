<?php
    namespace util;

    require_once './model/Review.php';
    require_once './util/Scraper.php';

    use \model\Review;
    use \DOMDocument;
    use \DOMXPath;
    use \util\Scraper;

    class ReviewScraper
        extends Scraper
    {
        public function getReviews(
            string $url,
            string $keyword,
            string $urlSuffix,
            bool $new
        ): array
        {
            // create search URL
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

        private function searchReviews(array $links, bool $new): array
        {
            $reviewsFound = array();

            // explore every link
            foreach ($links as $link)
            {
                $attributes = $this->extractAttributes($link);

                if ($attributes['title'] != NULL &&
                    $attributes['author'] != NULL) {
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
            }

            return $reviewsFound;
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
