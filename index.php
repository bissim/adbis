<?php
    /**
     * Created by PhpStorm.
     * User: mauro
     * Date: 14/09/2018
     * Time: 12:56
     */

    // These lines are for DEVELOPMENT only.
    // You should never display errors
    // in a production environment.
    // TODO comment following two lines
//    error_reporting(E_ALL);
//    ini_set('display_errors', '1');

    require './vendor/autoload.php';
    require './controller/SearchController.php';

    use \controller\SearchController;

    Flight::set('flight.log_errors', true);
    Flight::set('flight.views.path', './view');

    Flight::map('error', function(\Throwable $th) {
        // Handle error
    //    echo $th->getTraceAsString();
        echo "An error occurred in {$th->getFile()}, check server logs";
        error_log(
            "An error occurred: {$th->getMessage()}" .
            " in {$th->getFile()}" .
            " line {$th->getLine()}" .
            " (code {$th->getCode()})."
        );
    });

    Flight::route('/', function() {
        Flight::render('index.php');
    });

    Flight::route('/search', function(){
        // retrieve request
        $request = Flight::request();

        // extract data from request
        $table = $request->query['table'];
        $search = $request->query['search'];
        $keyword = $request->query['keyword'];

        // manage data if they exist
        if ($table && $search && $keyword)
        {
            $controller = new SearchController;
            $controller->search($table, $search, $keyword);
        }
        else
        {
            Flight::render('index.php');
        }
    });

    Flight::route('/test', function() {
        try {
            $request = Flight::request();
            $keyword = $request->query['x'];
            echo "GET request: {$keyword}";
        }
        catch (\Exception $ex)
        {
            user_error("Some sort of exception occurred: {$ex->getMessage()}");
        }
        catch (\Error $er)
        {
            error_log("Some sort of error occurred: {$er->getMessage()}");
        }
        catch (\Throwable $t)
        {
            error_log("A weird and mysterious error happened: {$t->getMessage()}");
        }
    });

    Flight::start();
