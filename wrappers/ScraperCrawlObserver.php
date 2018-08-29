<?php

namespace wrappers;

require_once '../vendor/autoload.php';
require_once 'BookScraper.php';

use \Spatie\Crawler\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use \wrappers\BookScraper;

class ScraperCrawlObserver extends CrawlObserver
{

    private $bookScraper;

    public function __construct(BookScraper $bookScraper)
    {
        $this->bookScraper = $bookScraper;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url)
    {
//        echo "About to crawl $url" . PHP_EOL . "<br />;
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    )
    {
        $path = $url->getPath();
        echo "{$path} found" . PHP_EOL . "<br />";

        // check if retrieved URL is valid
//        $count = 0;
//        $keywords = explode(' ', $this->bookScraper->getKeyword());
//        str_replace(
//            $path,
//            '',
//            $keywords,
//            $count);

        // URL has to be retrieved from here
//        echo "{$count} occurrences";
//        if ($count > 0)
//        {
//            echo 'crawled ' . var_dump($path) . PHP_EOL . '<br />';
//            $this->bookScraper->addUrl($url);
//        }
//        else
//        {
//            echo 'not crawled: ' . var_dump($path) . PHP_EOL . '<br />';
//        }
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    )
    {
        echo "Failed to crawl $url, reason: {$requestException->getMessage()}" .
            PHP_EOL . "<br />";
//        throw $requestException;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        echo "Scraping over!";
    }
}
