<?php
namespace wrappers;

require_once '../vendor/autoload.php';
require '../model/Book.php';
require 'CrawlAmazonBooksProfile.php';
require 'ScraperCrawlObserver.php';

use \model\Book;
use \DOMDocument;
use \DOMXPath;
use \Spatie\Crawler\Crawler;
use \wrappers\CrawlAmazonBooksProfile;
use \wrappers\ScraperCrawlObserver;

class BookScraper
{

    private $queries;
    private $scrapedUrls;
    private $keyword;

    public function __construct()
    {
        $this->scrapedUrls = array();
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function setQueries(array $queries)
    {
        $this->queries = $queries;
    }

    public function addUrl($url)
    {
        array_push($this->scrapedUrls, $url);
    }

    public function getKeyword(): string
    {
        return $this->keyword;
    }

    public function getBooks(string $url, string $keyword, string $urlSuffix): array
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
        // foreach ($entries as $entryLink)
        for ($i = 0; $i < 1; $i++) // TODO test ebook retrieval
        {
            // $l = $entryLink->firstChild->nodeValue;
            $l = $entries[$i]->firstChild->nodeValue;
            array_push($links, $l);
        }

        // retrieve books from links
        // as array of Book objects
        $books = $this->searchBooks($links);

        return $books;
    }

    public function retrieveLinks(string $baseUrl, string $keyword, string $urlSuffix) // TODO make private
    {
        $this->keyword = $keyword;
        $url = $baseUrl . $keyword. $urlSuffix;

        Crawler::create()
            ->setCrawlProfile(new CrawlAmazonBooksProfile($url))
            ->setCrawlObserver(new ScraperCrawlObserver($this))
            ->setConcurrency(20)
            ->setMaximumDepth(1)
//            ->setMaximumCrawlCount(100)
            ->startCrawling($url);
    }

    private function createDOMXPath(string $url): DOMXPath
    {
        // load page
        // $page = file_get_contents($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
			"Accept-Language: en-US,en;q=0.8,it;q=0.6",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $page = curl_exec($curl);
        if (curl_errno($curl)) // check for execution errors
        {
            echo 'Scraper error ' . curl_errno($curl) . ": " . curl_error($curl);
            exit;
        }
        curl_close($curl);

        // create DOM
        $dom = new DOMDocument;
        // libxml_use_internal_errors(true);
        $dom->loadHTML($page);
        // foreach (libxml_get_errors() as $error) {
            // TODO log errors into file
            // $errors .= $error->message."<br/>";
        // }
        // libxml_clear_errors();

        // create XPath from DOM
        $xpath = new DOMXPath($dom);

        return $xpath;
    }

    private function searchBooks(array $links): array
    {
        $booksFound = array();

        // explore every link
        foreach ($links as $link)
        {
            $title = $this->extractAttribute($link, $this->queries['title']);
            $author = $this->extractAttribute($link, $this->queries['author']);
            $price = \floatval($this->extractAttribute($link, $this->queries['price']));
            $editor = $this->extractAttribute($link, $this->queries['editor']);
            $image = $this->extractAttribute($link, $this->queries['image']);

            // create a Book object and put it in array
            $book = new Book(
                $title,
                $author,
                $price,
                $image,
                $link,
                $editor
            );
            array_push($booksFound, $book);
        }

        return $booksFound;
    }

    private function extractAttribute(string $link, string $query): string
    {
        $xpath = $this->createDOMXPath($link);
        $entries = $xpath->query($query);
        $attribute = $entries[0]->nodeValue;

        return $attribute;
    }
}
