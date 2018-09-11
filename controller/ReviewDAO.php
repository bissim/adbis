<?php

namespace controller;

require '../model/DAO.php';
require_once '../model/Review.php';
require '../controller/DBManager.php';

use model\DAO;
use model\Review;
use controller\DBManager;

class ReviewDAO implements DAO
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
        if (!($entity instanceof Review))
        {
            throw new \Exception('Object not instance of Review!');
        }

        // connect to database
        $this->connect();

        $review = $entity;

        $instruction = "
            INSERT INTO review (title, author, plot, txt, average, style, content, pleasantness)
            VALUES (:title, :author, :plot, :txt, :average, :style, :content, :pleasantness)
        ";
        $params = array(
            ':title' => $review->getTitle(),
            ':author' => $review->getAuthor(),
            ':plot' => $review->getPlot(),
            ':txt' => $review->getText(),
            ':average' => $review->getAvg(),
            ':style' => $review->getStyle(),
            ':content' => $review->getContent(),
            ':pleasantness' => $review->getPleasantness()
        );
        $this->dbMan->execute($instruction, $params);
        $this->dbMan->disconnect();

        // return persisted object
        return $review;
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
        if (!($entity instanceof Review))
        {
            throw new \Exception('Object not instance of Review!');
        }

        // connect to database
        $this->connect();

        // persist book into database
        $review = $entity;
        $instruction = "
            SELECT * FROM review WHERE id = :id
        ";
        $params = array(
            ':id' => $review->getId()
        );
        $results = $this->dbMan->query($instruction, $params);
        $this->dbMan->disconnect();

        // return persisted object
        if (!$results[0]) // TODO check
        {
            throw new \Exception("Review with id {$review->getId()} not found!");
        }

        $review = new Review (
            $results[0]["title"],
            $results[0]["author"],
            $results[0]["plot"],
            $results[0]["txt"],
            $results[0]["average"],
            $results[0]["content"],
            $results[0]["style"],
            $results[0]["pleasantness"]
        );
        
        return $review;
    }

    public function update(object $entity): object
    {

    }

    public function delete(object $entity): void
    {
        return;
    }
}