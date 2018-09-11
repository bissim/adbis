<?php

namespace controller;

require_once '../model/Book.php';

use \model\Book;

class DBManager
{
    private $serverName;
    private $username;
    private $password;
    private $dbName;
    private $port;
    private $pdo;

    public function __construct(
        string $server,
        string $user,
        string $pass,
        string $db,
        string $port
    )
    {
        $this->serverName = $server;
        $this->username = $user;
        $this->password = $pass;
        $this->dbName = $db;
        $this->port = $port;
    }

    // Connessione al database
    public function isConnected(): bool
    {
        return $this->pdo ? true : false;
    }

    /**
     * @throws \PDOException
     */
    public function connect(): void
    {

        $pdoOptions = array(
            \PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //make the default fetch be an associative array
            \PDO::ATTR_PERSISTENT => true // make connections reusable
        );

        try {
            $this->pdo = new \PDO(
                "mysql:host=$this->serverName;" .
                "dbname=$this->dbName",
                $this->username,
                $this->password,
                $pdoOptions
            );
        }
        catch (\PDOException $pdoe)
        {
            error_log("A database error occurred: $pdoe->getMessage().");
        }
        catch (\Exception $e)
        {
            error_log("An error occurred: $e->getMessage().");
        }
    }

    public function disconnect(): void
    {
        $this->pdo = null;
    }

    public function execute(string $instruction, array $params): void
    {
        // execute instruction
        $stmt = $this->pdo->prepare($instruction);
        $stmt->execute($params);

        // dismiss statement
        $stmt = null;
    }

    public function query(string $instruction, array $params): array
    {
        // execute query
        $stmt = $this->pdo->prepare($instruction);
        $stmt->execute($params);

        // retrieve results
        $results = $stmt->fetchAll();

        // dismiss statement
        $stmt = null;

        return $results;
    }

    public function __toString()
    {
        return $this->pdo ? "Connected to $this->username@$this->serverName." : "Not connected.";
    }

    // Restituisce i libri con un certo titolo
    public function getBooksByTitle(string $title) : array
    {
        $mysqli = $this->connect();
        $query = "SELECT Title, Author, Price, Image, Link, Editor FROM Book
                    WHERE Title LIKE '%" . $title . "%'";
        $result = mysqli_query ($mysqli, $query);
        mysqli_close($mysqli);
        return $this->buildBooksArray($result);
    }

    // Restituisce i libri di un certo autore
    public function getBooksByAuthor(string $author) : array
    {
        $mysqli = $this->connect();
        $query = "SELECT Title, Author, Price, Image, Link, Editor FROM Book
                    WHERE Author LIKE '%" . $author . "%'";
        $result = mysqli_query ($mysqli, $query);
        mysqli_close($mysqli);
        return $this->buildBooksArray($result);
    }

    // Per ogni record nella tabella Book del database
    // crea un oggetto di tipo Book
    // restituendo l'array di tali oggetti
    private function buildBooksArray ($result) : array
    {
        $books = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $title = $row["Title"];
                $author = $row["Author"];
                $price = $row["Price"];
                $image = $row["Image"];
                $link = $row["Link"];
                $editor = $row["Editor"];

                array_push(
                    $books,
                    new Book($title, $author, $price, $image, $link, $editor)
                );
            }
        }
        return $books;
    }

    // Rimuove tutti i record con un timestamp più vecchio di 30 giorni
    private function updateDB()
    {
        $mysqli = $this->connect();
        $query = "DELETE FROM Book
                    WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) > Book.TIMESTAMP";
        mysqli_query ($mysqli, $query);
        mysqli_close($mysqli);        
    }
}
