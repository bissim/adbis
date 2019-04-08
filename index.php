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
    //    setlocale(LC_ALL, '');

    require './vendor/autoload.php';
    require './controller/SearchController.php';
    require './test/ReviewWrapperTest.php';
    require './test/AmazonWrapperTest.php';
    require './test/GoogleWrapperTest.php';
    require './test/KoboWrapperTest.php';
    require './test/AudibleWrapperTest.php';
    require './test/NarratoreWrapperTest.php';
    require './test/MediatorTest.php';
    require './test/BookTest.php';
    require './test/ReviewTest.php';
    require './test/UTF8LevenshteinTest.php';
    require './test/DBManagerTest.php';
    require './test/TokenizerTest.php';

    use \controller\SearchController;

    // Flight configuration
    Flight::set('flight.log_errors', true);
    Flight::set('flight.views.path', './view');
//    Flight::set('flight.base_url', './');

    // handle errors
    Flight::map('error', function (Throwable $th) {
        // Handle error
    //    echo $th->getTraceAsString();
//        echo "An error occurred in {$th->getFile()}, check server logs";
        echo $th->getMessage();
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

    Flight::route('/audiobooks', function () {
        Flight::render('audiobooks.php');
    });

    Flight::route('/reviews', function () {
        Flight::render('reviews.php');
    });

    Flight::route('/contacts', function () {
        Flight::render('contact.php');
    });

    Flight::route('/about', function () {
        Flight::render('about.php');
    });

   Flight::route('/search/news', function () {
        (new SearchController)->searchNews();
    });

    Flight::route('/search/book', function () {
        // retrieve request
        $request = Flight::request();

        // extract data from request
        $search = $request->query['search'];
        $keyword = $request->query['keyword'];
        $depth = $request->query['depth'];
        $ajax = $request->ajax;

        // manage data if they exist
        if ($search && $keyword)
        {
            $controller = new SearchController;
            $controller->searchEBooks($search, $keyword, $depth, $ajax);
        }
        else
        {
            Flight::redirect('/');
        }
    });

    Flight::route('/search/audioBook', function () {
        // retrieve request
        $request = Flight::request();

        // extract data from request
        $search = $request->query['search'];
        $keyword = $request->query['keyword'];
        $depth = $request->query['depth'];
        $ajax = $request->ajax;

        // manage data if they exist
        if ($search && $keyword)
        {
            $controller = new SearchController;
            $controller->searchAudioBooks($search, $keyword, $depth, $ajax);
        }
        else
        {
            Flight::redirect('/');
        }
    });

    // tests
    Flight::route('/test/book', function () {
        (new \test\BookTest)->test();
    });

    flight::route('/test/review', function () {
        (new \test\ReviewTest)->test();
    });

    Flight::route('/test/qreview', function () {
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

    Flight::route('/test/audiobook', function () {
        (new \test\AudibleWrapperTest)->test();
    });

    Flight::route('/test/narratore', function () {
        (new \test\NarratoreWrapperTest)->test();
    });

    Flight::route('/test/mediator', function () {
        (new \test\MediatorTest)->test();
    });

    Flight::route('/test/levenshtein', function () {
        (new \test\UTF8LevenshteinTest)->test();
    });

    Flight::route('/test/dbmanager', function () {
        (new \test\DBManagerTest)->test();
    });

    Flight::route('/test/tokenizer', function () {
        (new \test\TokenizerTest)->test();
    });

    // run Fight router
    Flight::start();
