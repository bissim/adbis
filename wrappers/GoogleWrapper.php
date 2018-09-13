<?php

namespace wrappers;

require_once '../model/Book.php';

use \model\Book;

class GoogleWrapper
{
    private $baseurl = 'https://www.googleapis.com/books/v1/volumes?q=';

    public function __construct()
    {

    }

    public function getBooks(string $keyword) : array
    {
        $keyword = str_replace(' ', '', strtolower(trim($keyword)));
        $urlSearch = $this->baseurl . $keyword;

        $response = file_get_contents($urlSearch);
        $decoded = json_decode($response, TRUE);

        $books = array();

        foreach ($decoded['items'] as $item)	{
            $lng = $item['volumeInfo']['language'];
            $abl = $item['saleInfo']['saleability'];

            if ($lng != 'it' || $abl != 'FOR_SALE')
                continue;

            $t = $item['volumeInfo']['title'];
            $a = $item['volumeInfo']['authors'][0];
            $p = $item['saleInfo']['listPrice']['amount'];
            $i = $item['volumeInfo']['imageLinks']['smallThumbnail'];
            $l = $item['volumeInfo']['infoLink'];
            $e = $item['volumeInfo']['publisher'];
            
            array_push($books, new Book($t, $a, $p, $i, $l, $e));
        }

        return $books;
    }
}














?>