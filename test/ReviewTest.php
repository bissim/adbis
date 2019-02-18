<?php
    namespace test;

    require_once './test/BasicTest.php';
    require_once './model/Review.php';

    use \test\BasicTest;
    use \model\Review;

    class ReviewTest
        extends BasicTest
    {
        public function test()
        {
            echo 'Print a review<br />';
            $review = new Review(
                'Title review',
                'Review author',
                'Yea imagine dis a plot',
                'What? This is supposed to be the actual review',
                4.0,
                4.0,
                4.0,
                4.0
            );
            print_r($review);
            echo "<br />$review";
            echo '<br />Review printed!<hr />';

            echo 'Print a new review<br />';
            $newReview = new Review(
                'New review title',
                'New review author',
                'UNIT PLOT',
                'New review text',
                5.,
                5.,
                5.,
                5.,
                true
            );
            print_r($newReview);
            echo "<br />$newReview";
            echo '<br />New review printed!';
        }
    }
