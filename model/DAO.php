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
    public function retrieve(object $entity): object;
    public function update(object $entity): object;
    public function delete(object $entity): void;
}
