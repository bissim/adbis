<?php
namespace wrappers;

require '..\model\Book.php';

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

    private function createDOMXPath(string $link): DOMXPath
    {
        // load page
        $page = file_get_contents($link);

        // create DOM
        $dom = new DOMDocument;
        $dom->loadHTML($page);

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
