<?php
/**
     * Created by PhpStorm.
     * User: bisim
     * Date: 09/09/2018
     * Time: 01:22
     */

namespace model;


interface DAO
{
    public function create(object $entity): object;
    public function retrieveById(object $entity): object;
    public function retrieveByTitle(string $title): array;
    public function retrieveByAuthor(string $author): array;
    public function update(object $entity): object;
    public function delete(object $entity): void;
}
