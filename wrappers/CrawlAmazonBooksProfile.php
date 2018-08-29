<?php
    /**
     * Created by PhpStorm.
     * User: mauro
     * Date: 29/08/2018
     * Time: 22:47
     */

    namespace wrappers;

    require_once '../vendor/autoload.php';

    use Psr\Http\Message\UriInterface;
    use Spatie\Crawler\CrawlInternalUrls;

    class CrawlAmazonBooksProfile extends CrawlInternalUrls
    {
        public function __construct($baseUrl)
        {
            parent::__construct($baseUrl);
        }

        public function shouldCrawl(UriInterface $url): bool
        {
            $path = $url->getPath();

            if (
                0 > strpos($path, '/gp/') ||
                !parent::shouldCrawl($url)
            )
            {
                return false;
            }

            return true;
        }
    }