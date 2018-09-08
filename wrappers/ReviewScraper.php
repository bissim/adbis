<?php
namespace wrappers;

require_once '../vendor/autoload.php';
require '../model/Review.php';

use \model\Book;
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

    public function getReviews(string $url, string $keyword, string $urlSuffix): array
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
            array_push($links, $l);
        }

        // retrieve reviews from links
        // as array of Review objects
        $reviews = $this->searchReviews($links);

        return $reviews;
    }

    private function get_web_page(string $url) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "adbis", // name of client
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
        $page = $this->get_web_page($url);

        // create DOM
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($page);
        libxml_clear_errors();

        // create XPath from DOM
        $xpath = new DOMXPath($dom);

        return $xpath;
    }

    private function searchReviews(array $links): array
    {
        $reviewsFound = array();

        // explore every link
        foreach ($links as $link)
        {
            $title = $this->extractAttribute($link, $this->queries['title']);
            $author = $this->extractAttribute($link, $this->queries['author']);
            $plot = $this->extractAttribute($link, $this->queries['plot']);
            $text = $this->extractAttribute($link, $this->queries['text']);
            $avg = \floatval($this->extractAttribute($link, $this->queries['avg']));
            $style = \floatval($this->extractAttribute($link, $this->queries['style']));
            $content = \floatval($this->extractAttribute($link, $this->queries['content']));
            $pleasantness = \floatval($this->extractAttribute($link, $this->queries['pleasantness']));

            // create a Review object and put it in array
            $review = new Review(
                $title,
                $author,
                $plot,
                $text,
                $avg,
                $style,
                $content,
                $pleasantness
            );
            array_push($booksFound, $book);
        }

        return $reviewsFound;
    }

    private function extractAttribute(string $link, string $query): string
    {
        $xpath = $this->createDOMXPath($link);
        $entries = $xpath->query($query);
        $attribute = $entries[0]->nodeValue;

        if (empty($attribute))
            return '';
        return $attribute;
    }
}
