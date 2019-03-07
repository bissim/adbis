<?php
    namespace util;

    use \DOMDocument;
    use \DOMXPath;

    abstract class Scraper
    {
        protected $queries;

        public function getQueries(): array
        {
            return $this->queries;
        }

        public function setQueries(array $queries)
        {
            $this->queries = $queries;
        }

        protected function getWebPage(string $url)
        {
            $headers = array(
                "Content-type: text/html; charset=\"utf-8\"",
            );

            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLOPT_ENCODING       => '',       // handle compressed
                CURLOPT_USERAGENT      => 'adbis',  // name of client
                CURLOPT_AUTOREFERER    => true,     // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // time-out on connect
                CURLOPT_TIMEOUT        => 120,      // time-out on response,
                CURLOPT_HTTPHEADER     => $headers, //
            );

            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);

            return $content;
        }

        protected function createDOMXPath(string $url): DOMXPath
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

        protected function checkEmpty($result): string
        {
            if ($result && $result->length > 0)
            {
                $value = $result[0]->nodeValue;
                return empty($value) ? '' : $value;
            }

            return '';
        }

        protected function checkFloat($result): float
        {
            if ($result && $result->length > 0)
            {
                $value = $result[0]->nodeValue;
                if (empty($value)) return 0.0;
                $value = preg_replace('/[^0-9,.]/', '', $value);
                $value = str_replace(',', '.', $value);
                return (float) \floatval($value);
            }

            return 0.0;
        }

        protected function checkNum($entriesValue): float
        {
            if ($entriesValue && $entriesValue->length > 0)
            {
                $value = $entriesValue[0]->nodeValue;
                return (float) \floatval($value);
            }

            return 0.0;
        }
    }
