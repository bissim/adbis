<?php
/**
 * Created by PhpStorm.
 * User: mauro
 * Date: 12/09/2018
 * Time: 10:10
 */

namespace test;

$configs = parse_ini_file('../db/dbconfig.ini', true);

$serverProperty = $configs['connection']['server'];

user_error("DB Server: {$serverProperty}");

echo 'Parse successful!';
