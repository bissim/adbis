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
    require './test/ReviewWrapperTest.php';
    require './test/AmazonWrapperTest.php';
    require './test/GoogleWrapperTest.php';
    require './test/KoboWrapperTest.php';
    require './test/MediatorTest.php';

    use \controller\SearchController;

    // Flight configuration
    Flight::set('flight.log_errors', true);
    Flight::set('flight.views.path', './view');
//    Flight::set('flight.base_url', './');

    // handle errors
    Flight::map('error', function (Throwable $th) {
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

    // redirect non-existing page to main page
    Flight::map('notFound', function () {
//        echo 'Whoops! Seems like there\'s no page like that!';
//        \sleep(5);
        Flight::redirect('/');
    });

    // Flight routes
    Flight::route('/', function () {
        Flight::render('index.php');
    });

    Flight::route('/ebooks', function () {
        Flight::render('ebooks.php');
    });

    Flight::route('/reviews', function () {
        Flight::render('reviews.php');
    });

    Flight::route('/search', function () {
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
            $controller->search($table, $search, $keyword, $request->ajax);
        }
        else
        {
            Flight::redirect('/');
        }
    });

    Flight::route('/search/book', function () {
        // retrieve request
        $request = Flight::request();

        // extract data from request
        $search = $request->query['search'];
        $keyword = $request->query['keyword'];
        $join = $request->query['join'];
        $ajax = $request->ajax;

        // manage data if they exist
        if ($search && $keyword)
        {
            $controller = new SearchController;
            switch ($join) // TODO check
            {
                case 'true':
                    $controller->searchBoth($search, $keyword, $ajax);
                    break;
                case 'false':
                    $controller->searchBook($search, $keyword, $ajax);
                    break;
                default:
                    throw new \Exception("wtf");
                    break;
            }
        }
        else
        {
            Flight::redirect('/');
        }
    });

    Flight::route('/search/review', function () {
        // retrieve request
        $request = Flight::request();

        // extract data from request
        $search = $request->query['search'];
        $keyword = $request->query['keyword'];

        // manage data if they exist
        if ($search && $keyword)
        {
            $controller = new SearchController;
            $controller->searchReview($search, $keyword, $request->ajax);
        }
        else
        {
            Flight::redirect('/');
        }
    });

    Flight::route('/old', function () {
        Flight::render('oldIndex.php');
    });

    // tests
    Flight::route('/test/review', function () {
        (new \test\ReviewWrapperTest)->test();
    });

    Flight::route('/test/amazonbook', function () {
        (new \test\AmazonWrapperTest)->test();
    });

    Flight::route('/test/googlebook', function () {
        (new \test\GoogleWrapperTest)->test();
    });

    Flight::route('/test/kobobook', function () {
        (new \test\KoboWrapperTest)->test();
    });

    Flight::route('/test/mediator', function () {
        (new \test\MediatorTest)->test();
    });

    // run Fight router
    Flight::start();
