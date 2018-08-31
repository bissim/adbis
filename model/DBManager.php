<?php

namespace model;

require_once 'Book.php';

use \model\Book;

class DBManager
{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;


    public function __construct(
        string $server,
        string $user,
        string $pass,
        string $db
    )
    {
        $this->servername = $server;
        $this->username = $user;
        $this->password = $pass;
        $this->dbname = $db;
    }

    private function connect()
    {
        return mysqli_connect($this->servername, $this->username,
                                        $this->password, $this->dbname);
     }

    public function addBooks(array $books)
    {
        $mysqli = $this->connect();

        foreach ($books as $book)
        {
            $query = "INSERT INTO Book (Title, Author, Price, Image, Link, Editor)
                        VALUES ('" . $book->getTitle() . "','" . $book->getAuthor() . "'," .
                                $book->getPrice() . ",'" . $book->getImg() . "','" .
                                $book->getLink() . "','" . $book->getEditor() . "')";
            mysqli_query($mysqli, $query);            
        }
        mysqli_close($mysqli);
    }

    public function getBooksByTitle(string $title) : array
    {
        $mysqli = $this->connect();
        $query = "SELECT Title, Author, Price, Image, Link, Editor FROM Book
                    WHERE Title LIKE '%" . $title . "%'";
        $result = mysqli_query ($mysqli, $query);
        mysqli_close($mysqli);
        return $this->buildBooksArray($result);
    }

    public function getBooksByAuthor(string $author) : array
    {
        $mysqli = $this->connect();
        $query = "SELECT Title, Author, Price, Image, Link, Editor FROM Book
                    WHERE Author LIKE '%" . $author . "%'";
        $result = mysqli_query ($mysqli, $query);
        mysqli_close($mysqli);
        return $this->buildBooksArray($result);
    }

    private function buildBooksArray ($result) : array
    {
        $books = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $t = $row["Title"];
                $a = $row["Author"];
                $p = $row["Price"];
                $i = $row["Image"];
                $l = $row["Link"];
                $e = $row["Editor"];
                array_push($books, new Book($t, $a, $p, $i, $l, $e));
            }
        }
        else return NULL;

        return $books;

    }

}

?>