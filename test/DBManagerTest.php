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


// $dbMan = new DBManager('localhost', 'adbis', '123456', 'adbis_db', '3306');
$dbMan = new DBManager;
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
