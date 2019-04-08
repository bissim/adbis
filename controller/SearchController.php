<?php
    namespace controller;

    /**
     * Created by PhpStorm.
     * User: mauro
     * Date: 14/09/2018
     * Time: 16:58
     */

    require_once './vendor/autoload.php';
    require_once './controller/Mediator.php';

    use \Throwable;
    use Flight;

    class SearchController
    {
        
        // Cerca i nuovi ebook
        public function searchNews()
        {
            $mediator = new Mediator;
            echo $mediator->getNewItems();
        }

        public function searchEbooks(string $search, string $keyword, string $depth, bool $ajax)
        {
            $result = '';
            $join = '';
            $mediator = new Mediator;

            try
            {
                $result = $mediator->retrieve('ebook', $search, $keyword, $depth);

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
                Flight::render(
                    'index',
                    array(
                        'result' => $result,
                        'join' => $join
                    )
                );
            }
        }

        public function searchAudioBooks(string $search, string $keyword, string $depth, bool $ajax)
        {
            $result = '';
            $join = '';
            $mediator = new Mediator;

            try
            {
                $result = $mediator->retrieve('audiobook', $search, $keyword, $depth);

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
                Flight::render(
                    'index',
                    array(
                        'result' => $result,
                        'join' => $join
                    )
                );
            }
        }
    }
