<?php
    namespace wrappers;

    require_once './model/Book.php';

    use \model\Book;

    class GoogleWrapper
    {
        private $baseUrl = 'https://www.googleapis.com/books/v1/volumes?q=';

        public function __construct()
        {}

        public function getBooks(string $keyword): array
        {
            $keyword = str_replace(
                ' ',
                '',
                strtolower(trim($keyword))
            );
            $urlSearch = $this->baseUrl . $keyword;

            $response = file_get_contents($urlSearch);
            $decoded = json_decode($response, true);

            $books = array();

            foreach ($decoded['items'] as $item)
            {
                $lng = $item['volumeInfo']['language'];
                $abl = $item['saleInfo']['saleability'];

                if ($lng != 'it' || $abl != 'FOR_SALE')
                {
                    continue;
                }

                $title = $item['volumeInfo']['title'];
                $author = $item['volumeInfo']['authors'][0];
                $price = $item['saleInfo']['listPrice']['amount'];
                $image = $item['volumeInfo']['imageLinks']['smallThumbnail'];
                $link = $item['volumeInfo']['infoLink'];
//                $e = $item['volumeInfo']['publisher'];

                array_push(
                    $books,
                    new Book(
                        $title,
                        $author,
                        $price,
                        $image,
                        $link
                    )
                );
            }

            return $books;
        }
    }
