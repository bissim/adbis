<?php
    namespace controller;

    /**
     * Created by PhpStorm.
     * User: mauro
     * Date: 14/09/2018
     * Time: 16:58
     */

    require_once './vendor/autoload.php';
    require './controller/Mediator.php';

    use \Throwable;
    use Flight;

    class SearchController
    {
        public function search(string $table, string $search, string $keyword, bool $ajax)
        {
            // use mediator
            $result = '';
            try
            {
                $result = (new Mediator())->retrieve($table, $search, $keyword);
            }
            catch (Throwable $th)
            {
                user_error(
                    "An error occurred: {$th->getMessage()}" .
                    " in {$th->getFile()}" .
                    " line {$th->getLine()}" .
                    " (code {$th->getCode()})."
                );
            }

            // show result in view
            if ($ajax)
            {
                echo $result;
            }
            else
            {
                Flight::view()->set('result', $result);
                Flight::render('example');
//                Flight::render(
//                    'example.php',
//                    array(
//                        'result' => $result
//                    )
//                );
            }
        }
    }
