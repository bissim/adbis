<?php

require '../controller/BookDAO.php';
require '../controller/ReviewDAO.php';

use controller\BookDAO;
use controller\ReviewDAO;

class DAOManager {
    private $bookDAO;
    private $reviewDao;

    public function __construct()
    {
        $this->bookDAO = new BookDAO();
        $this->reviewDAO = new ReviewDAO();
    }

    // Ricerca i libri nel database in base all'autore o al titolo
    public function getBooks(string $search, string $keyword): array
    {
        $books;
        switch ($search) {
            case 'author': $books = $this->bookDAO->retrieveByAuthor($keyword); break;
            case 'title' : $books = $this->bookDAO->retrieveByTitle($keyword);
        }
        return $books;
    }

    // Aggiunge libri nel database
    public function addBooks(array $books)
    {
        foreach ($books as $book)
            $this->bookDAO->create($book);
    }

    // Ricerca le recensioni nel database in base all'autore o al titolo
    public function getReviews(string $search, string $keyword): array
    {
        $reviews;
        switch ($search) {
            case 'author': $reviews = $this->reviewDAO->retrieveByAuthor($keyword); break;
            case 'title' : $reviews = $this->reviewkDAO->retrieveByTitle($keyword);
        }
        return $reviews;
    }

    // Aggiunge libri nel database
    public function addReviews(array $reviews)
    {
        foreach ($reviews as $review)
            $this->reviewDAO->create($review);
    }    
}