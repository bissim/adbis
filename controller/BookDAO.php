<?php
/**
     * Created by PhpStorm.
     * User: mauro
     * Date: 09/09/2018
     * Time: 10:42
     */

namespace controller;

require '../model/DAO.php';
require '../model/Book.php';
require '../controller/DBManager.php';

use model\DAO;
use model\Book;
use controller\DBManager;


class BookDAO implements DAO
{

    public function create(object $entity): object
    {
        if (!($entity instanceof Book))
        {
            throw new \Exception('Object not instance of Book!');
        }

        $mysqli = new DBManager();
    }

    public function retrieve(object $entity): object
    {
        // TODO: Implement retrieve() method.
    }

    public function update(object $entity): object
    {
        // TODO: Implement update() method.
    }

    public function delete(object $entity): void
    {
        // TODO: Implement delete() method.
    }
}