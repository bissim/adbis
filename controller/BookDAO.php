<?php

namespace controller;

require '../model/DAO.php';
require_once '../model/Book.php';
require '../controller/DBManager.php';

use model\DAO;
use model\Book;
use controller\DBManager;

class BookDAO implements DAO
{

    private $dbMan;

    private function connect()
    {
        $this->dbMan = new DBManager(
            'localhost',
            'phpmyadmin',
            'pass',
            'adbis_db',
            '3306'
        );
        $this->dbMan->connect();
    }

    /**
     * @param object $entity
     *
     * @return object
     * @throws \Exception
     */

    public function create(object $entity): object
    {
        // check whether object is instance of book
        if (!($entity instanceof Book))
        {
            throw new \Exception('Object not instance of Book!');
        }

        // connect to database
        $this->connect();

        $book = $entity;

        $instruction = "
            INSERT INTO book (title, author, price, image, link)
            VALUES (:title, :author, :price, :image, :link)
        ";
        $params = array(
            ':title' => $book->getTitle(),
            ':author' => $book->getAuthor(),
            ':price' => $book->getPrice(),
            ':image' => $book->getImg(),
            ':link' => $book->getLink()
        );
        $this->dbMan->execute($instruction, $params);
        $this->dbMan->disconnect();

        // return persisted object
        return $book;
    }

    /**
     * @param object $entity
     *
     * @return object
     * @throws \Exception
     */
    
    public function retrieve(object $entity): object
    {
        // check whether object is instance of book
        if (!($entity instanceof Book))
        {
            throw new \Exception('Object not instance of Book!');
        }

        // connect to database
        $this->connect();

        // persist book into database
        $book = $entity;
        $instruction = "
            SELECT * FROM book WHERE id = :id
        ";
        $params = array(
            ':id' => $book->getId()
        );
        $results = $this->dbMan->query($instruction, $params);
        $this->dbMan->disconnect();

        // return persisted object
        if (!$results[0]) // TODO check
        {
            throw new \Exception("Book with id {$book->getId()} not found!");
        }

        $book = new Book (
            $results[0]["title"],
            $results[0]["author"],
            $results[0]["price"],
            $results[0]["image"],
            $results[0]["link"]
        );
        
        return $book;
    }

    public function update(object $entity): object
    {

    }

    public function delete(object $entity): void
    {
        return;
    }
}