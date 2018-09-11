<?php
/**
 * Created by PhpStorm.
 * User: bisim
 * Date: 10/09/2018
 * Time: 10:11
 */

namespace test;

require '../controller/DBManager.php';

use controller\DBManager;

$host = 'localhost';
$user = 'phpmyadmin';
$password = 'pass';
$database = 'adbis_db';
$port = '3306';

// $dbMan = new DBManager('localhost', 'adbis', '123456', 'adbis_db', '3306');
$dbMan = new DBManager($host, $user, $password, $database, $port);
echo "$dbMan<br />";
try {
    $dbMan->connect();
    echo "$dbMan<br />";
    echo $dbMan->isConnected() ? 'FUCK YEA' : 'NO FUCK NO';
}
catch (\Exception $e)
{
    error_log("An exception occurred: $e->getMessage().");
}
