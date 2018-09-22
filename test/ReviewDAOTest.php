<?php
    /**
     * Created by PhpStorm.
     * User: bisim
     * Date: 10/09/2018
     * Time: 09:35
     */

    namespace test;

    require_once './model/Review.php';
    require_once './controller/ReviewDAO.php';

    use model\Review;
    use controller\ReviewDAO;

    class ReviewDAOTest
    {
        public function test()
        {
            $reviewDao = new ReviewDAO;

            $review = new Review(
                'Un titolo',
                'Un autore',
                'Una trama',
                'Un testo',
                1.0,
                2.0,
                3.0,
                1.5
            );

            $newReview = new Review(
                'A new title',
                'A new author',
                'A new plot',
                'A new review text',
                4.,
                4.,
                4.,
                4.,
                true
            );

            $returnedReview = null;
            try
            {
                // test persisting review
                $returnedReview = $reviewDao->create($review);
                echo "Added review: $returnedReview";
                if (!$returnedReview)
                    $returnedReview = new Review(
                        'just a title',
                        'just an author',
                        'wtf do u rly want an plot?',
                        'wat',
                        1.3,
                        5.1,
                        4.1,
                        2.9
                    );
                $returnedReview->setId(1);
                echo "searching for review with ID {$returnedReview->getId()}...<br />";
                $returnedReview = $reviewDao->retrieveById($review);
                echo "Review retrieved: $returnedReview";

                // test persisting new review
                $returnedReview = $reviewDao->create($newReview);
                echo "<hr />Added new review: $returnedReview";
                if (!$returnedReview)
                    $returnedReview = new Review(
                        'just a title',
                        'just an author',
                        'just a plot',
                        'just a review text',
                        4.,
                        4.,
                        4.,
                        4.,
                        true
                    );
                $returnedReview->setId(2);
                echo "searching for new review with id {$returnedReview->getId()}...<br />";
                $returnedReview = $reviewDao->retrieveById($returnedReview);
                echo "Review retrieved: $returnedReview";
            }
            catch (\Exception $e)
            {
                $date = (new \DateTime())->format('Y-m-d H:i:s');
                error_log("[$date] An error occurred: {$e->getMessage()}");
            }
        }
    }
