<?php
    namespace test;

    require_once '../wrappers/AmazonWrapper.php';
    require_once '../wrappers/GoogleWrapper.php';
    require_once '../wrappers/KoboWrapper.php';

    use \wrappers\AmazonWrapper;
    use \wrappers\GoogleWrapper;
    use \wrappers\KoboWrapper;

    class WrappersTest
    {
        public function test()
        {
            $amazonWrapper = new AmazonWrapper;
            $googleWrapper = new GoogleWrapper;
            $koboWrapper = new KoboWrapper;

            $books = array_merge(
                $amazonWrapper->getBooks('il signore degli anelli'),
                $googleWrapper->getBooks('il signore degli anelli'),
                $koboWrapper->getBooks('il signore degli anelli')
            );

            foreach ($books as $book)
                print $book;

//             $amazonWrapper = new AmazonWrapper;
//             $aBooks = $amazonWrapper->getBooks('il signore degli anelli');
//
//             print '<h2>Libri su Amazon</h2>';
//             foreach ($aBooks as $aBook)
//                 print $aBook;
//
//             $googleWrapper = new GoogleWrapper;
//             $gBbooks = $googleWrapper->getBooks('il signore degli anelli');
//
//             print '<h2>Libri su Google</h2>';
//             foreach ($gBooks as $gBook)
//                 print $gBook;
//
//             $koboWrapper = new KoboWrapper;
//             $kBooks = $koboWrapper->getBooks('il signore degli anelli');
//
//             print '<h2>Libri su Kobo</h2>';
//             foreach ($kBooks as $kBook)
//                 print $kBook;
        }
    }
