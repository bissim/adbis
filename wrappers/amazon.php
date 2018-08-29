<?php

namespace wrappers;

require '..\model\Book.php';

use \model\Book;
use \DOMDocument;
use \DOMXPath;

function getAmazonBooks(string $keyword): array
{

        // L'URL della pagina dove appare il catalogo degli ebook cercati in base a una keyword
        $amazonURL = 'https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=';
        $keyword = str_replace(" ", "+", $keyword);
        $urlSearch = $amazonURL . $keyword;

        // La pagine viene caricate
        $page = file_get_contents($urlSearch);

        // Si crea un DOM della pagina e un XPath
        $dom = new DOMDocument;
        $dom->loadHTML($page);
        $xpath = new DOMXPath($dom);

        // La query per ottenere il link di ogni ebook dal catalogo mostrato
        $query = '//div[@class="a-row a-spacing-small"]/div[@class="a-row a-spacing-none"]/a/attribute::href';

        // Si estraggono i link
        $entries = $xpath->query($query);

        //echo $entriesLink->length . "<br>";

        // Per ogni ebook nel catalogo si estrae il link della sua pagina di acquisto e lo si inserisce in un array
        $links = array();
        // foreach ($entries as $entryLink)
        for ($i = 0; $i < 1; $i++) // TODO test ebook retrieval
        {
                // $l = $entryLink->firstChild->nodeValue;
                $l = $entries[$i]->firstChild->nodeValue;
                array_push($links, $l);
        }

        $books = searchBooks($links);

        return $books;
}

// L'array dei libri
//$books = array();

function searchBooks(array $links): array
{

        $booksFound = array();

        // Per ogni link corrispondente ad un ebook
        foreach ($links as $link)
        {
                // Bisogna estrarre i parametri dell'ebook nella sua pagina
                $page = file_get_contents($link);
                $dom = new DOMDocument();
                $dom->loadHTML($page);
                $xpath = new DOMXPath($dom);

                // La query per ottenere il titolo
                $query = '//div[@id="centerCol"]/div/div/h1/span[@id="ebooksProductTitle"]/text()';
                $entries = $xpath->query($query);

                // La variabile in cui mantengo il titolo trovato
                // sarebbe necessario estrarre il primo elemento da entriesTitle
                // ma al momento non ho capito come fare
                // quindi ho dovuto fare un for ignorante
                $t = $entries[0]->nodeValue;
                // foreach ($entries as $entryTitle)
                // {
                //         $t = $entryTitle->nodeValue;
                // }

                // la query per ottenere l'autore
                $query = '//div[@id="centerCol"]/div[@id="booksTitle"]/div[@id="bylineInfo"]/span/span[@class="a-declarative"]/a[1]/text()';
                $entries = $xpath->query($query);

                // Stessa storia per gli altri parametri; autore, prezzo, ecc.
                $a = $entries[0]->nodeValue;
                // foreach ($entries as $entryAutore)
                // {
                //         $a = $entryAutore->nodeValue;
                // }

                // la query per ottenere il prezzo
                $query = '//table[@class="a-lineitem a-spacing-micro"]//tr[@class="kindle-price"]/td[2]';
                $entries = $xpath->query($query);

                $p = $entries[0]->nodeValue;
                // foreach ($entries as $entryPrezzo)
                // {
                //         $p = $entryPrezzo->firstChild->nodeValue;
                // }
                $p = \floatval($p);

                // la query per ottenere l'editore
                $query = '//div[@id="detail_bullets_id"]/table//tr/td[@class="bucket"]/div/ul/li[4]/text()';
                $entries = $xpath->query($query);

                $e = $entries[0]->nodeValue;
                // foreach ($entries as $entryEditore)
                // {
                //         $e = $entryEditore->nodeValue;
                // }

                // la query per ottenere l'immagine di copertina
                $query = '//div[@id="leftCol"]/div[1]/div/div[2]/div/div/div/div/img/attribute::src';
                $entries = $xpath->query($query);

                $i = $entries[0]->nodeValue;
                // foreach ($entries as $entryImg)
                // {
                //         $i = $entryImg->nodeValue;
                // }

                // Creo un oggetto di tipo Book con i parametri trovati
                $b = new Book($t, $a, $p, $i, $link, $e);

                //Inserisco l'oggetto in un array
                array_push($booksFound, $b);
        }

        return $booksFound;
}

// TODO remove all the code after (and including) this line
$books = getAmazonBooks('il signore degli anelli');

// Controllo i parametri di ogni libro
foreach ($books as $book)
        print $book;
