<?php
/**
     * Created by PhpStorm.
     * User: mauro
     * Date: 09/09/2018
     * Time: 10:42
     */

namespace controller;

require '../model/DAO.php';
require_once '../model/Book.php';
require '../controller/DBManager.php';

use model\DAO;
use model\Book;
use controller\DBManager;


class BookDAO implements DAO
{
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
        $mysqli = new DBManager('localhost', 'adbis', '123456', 'adbis_db', '3306');
        $mysqli->connect();

        // persist book into database
        $book = $entity;
        $instruction = "
            INSERT INTO Book (title, author, price, image, link, editor)
            VALUES (:title, :author, :price, :image, :link, :editor)
        ";
        $params = array(
            ':title' => $book->getTitle(),
            ':author' => $book->getAuthor(),
            ':price' => $book->getPrice(),
            ':image' => $book->getImg(),
            ':link' => $book->getLink(),
            ':editor' => $book->getEditor()
        );
        $mysqli->execute($instruction, $params);
        $mysqli->disconnect();

        // return persisted object
        return $book;
    }

    public function retrieve(object $entity): object
    {
        // check whether object is instance of book
        if (!($entity instanceof Book))
        {
            throw new \Exception('Object not instance of Book!');
        }

        // connect to database
        $mysqli = new DBManager('localhost', 'adbis', '123456', 'adbis_db', '3306');
        $mysqli->connect();

        // persist book into database
        $book = $entity;
        $instruction = "
            SELECT * FROM book WHERE id = :id
        ";
        $params = array(
            ':id' => $book->getId()
        );
        $results = $mysqli->query($instruction, $params);
        $mysqli->disconnect();

        // return persisted object
        if (!$results[0]) // TODO check
        {
            $results[0] = new Book('', '', 0.0, '', '', '');
            $results[0]->setId(0);
        }
        return $results[0];
    }

    public function update(object $entity): object
    {
        return null; // TODO: Implement update() method.
    }

    public function delete(object $entity): void
    {
        return;
    }
}