<?php
namespace util;

require_once '../vendor/autoload.php';
require '../model/Review.php';

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
            $l = strstr($entryLink->firstChild->nodeValue, 'https://');
            array_push($links, $l);
        }

        // retrieve reviews from links
        // as array of Review objects
        $reviews = $this->searchReviews($links);

        return $reviews;
    }

    private function getWebPage(string $url) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => '',     // handle compressed
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Android 7.0; Mobile; rv:57.0) Gecko/57.0 Firefox/57.0', // name of client
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

    private function searchReviews(array $links): array
    {
        $reviewsFound = array();

        // explore every link
        foreach ($links as $link)
        {
            $attributes = $this->extractAttributes($link);


            // $title = $this->extractAttribute($link, $this->queries['title']);
            // $author = $this->extractAttribute($link, $this->queries['author']);
            // $plot = $this->extractAttribute($link, $this->queries['plot']);
            // $text = $this->extractAttribute($link, $this->queries['text']);
            // $avg = \floatval($this->extractAttribute($link, $this->queries['avg']));
            // $style = \floatval($this->extractAttribute($link, $this->queries['style']));
            // $content = \floatval($this->extractAttribute($link, $this->queries['content']));
            // $pleasantness = \floatval($this->extractAttribute($link, $this->queries['pleasantness']));

            // create a Review object and put it in array
            $review = new Review(
                $attributes['title'],
                $attributes['author'],
                $attributes['plot'],
                $attributes['text'],
                $attributes['avg'],
                $attributes['style'],
                $attributes['content'],
                $attributes['pleasantness']
            );
            array_push($reviewsFound, $review);
        }

        return $reviewsFound;
    }

    private function checkEmpty($value)
    {
        return empty($value) ? '' : $value;
    }

    // private function extractAttribute(string $link, string $query): string
    private function extractAttributes(string $link): array
    {
        $xpath = $this->createDOMXPath($link);

        $entriesTitle = $xpath->query($this->queries['title']);
        $title = $this->checkEmpty($entriesTitle[0]->nodeValue);

        $entriesAuthor = $xpath->query($this->queries['author']);
        $author = $this->checkEmpty($entriesAuthor[0]->nodeValue);

        $entriesPlot = $xpath->query($this->queries['plot']);
        $plot = $this->checkEmpty($entriesPlot[0]->nodeValue);

        $entriesText = $xpath->query($this->queries['text']);
        $text = $this->checkEmpty($entriesText[0]->nodeValue);        

        $entriesAvg = $xpath->query($this->queries['avg']);
        $stringAvg = $entriesPrice[0]->nodeValue;
        // $stringAvg = \substr($stringAvg,5);
        // $stringAvg = str_replace(',', '.', $stringAvg);
        // $avg = empty($stringAvg) ? 0.0 : (float) \floatval($stringAvg);        
        $avg = (float) \floatval($stringAvg);

        $entriesStyle = $xpath->query($this->queries['style']);
        $stringStyle = $entriesStylee[0]->nodeValue;
        $style = (float) \floatval($stringStyle);

        $entriesContent = $xpath->query($this->queries['content']);
        $stringContent = $entriesContent[0]->nodeValue;
        $content = (float) \floatval($stringContent);

        $entriesPleasentness = $xpath->query($this->queries['pleasantness']);
        $stringPleasentness = $entriesStylee[0]->nodeValue;
        $pleasentness = (float) \floatval($stringPleasentness);

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

        // if (empty($attribute))
        //     return '';
        // return $attribute;
    }
}
