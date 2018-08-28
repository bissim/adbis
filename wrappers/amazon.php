<?php

//La classe book ha come variabili d'istanza i parametri di un libro, ne possiamo aggiungere altri successivamente

class Book
{
        private $titolo;
        private $autore;
        private $prezzo;
        private $img;
        private $link;
        private $editore;

//Costruttore
        public function __construct($t, $a, $p, $i, $l, $e)
        {
        $this->titolo = $t;
        $this->autore = $a;
        $this->prezzo = $p;
        $this->img = $i;
        $this->link = $l;
        $this->editore = $e;
        }

//Una sorta di toString
        public function call()
        {
        echo "titolo: " . ($this->titolo) . "<br> autore: " . ($this->autore) . "<br> prezzo: " . ($this->prezzo) .
                "<br>immmagine: " . ($this->img) . "<br>link: " . ($this->link). "<br>editore: " . ($this->editore) . "<hr>";
        }

}

function getAmazonBooks($keyword)
{

//L'URL della pagina dove appare il catalogo degli ebook cercati in base a una keyword
$urlSearch = str_replace(" ", "+", "https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=" . $keyword);

//La pagine viene caricate
$page = file_get_contents($urlSearch);

//Si crea un DOM della pagina e un xpath
$DOM = new DOMDocument;
$DOM->loadHTML($page);
$xpath = new DOMXPath($DOM);

//La query per ottenere il link di ogni ebook dal catalogo mostrato
$queryLink = '//div[@class="a-row a-spacing-small"]/div[@class="a-row a-spacing-none"]/a/attribute::href';

//Si estraggono i link
$entriesLink = $xpath->query($queryLink);

//echo $entriesLink->length . "<br>";

//Per ogni ebook nel catalogo si estrae il link della sua pagina di acquisto e lo si inserisce in un array
$links = array();
foreach ($entriesLink as $entryLink)
{
        $l =  $entryLink->firstChild->nodeValue;
        array_push($links, $l);
}

$books = searchBooks($links);

return $books;
}

//L'array dei libri
//$books = array();

function searchBooks($links)
{

$booksFound = array();

//Per ogni link corrispondente ad un ebook
foreach ($links as $link)
{
	//Bisogna estrarre i parametri dell'ebook nella sua pagina
	$pageItem = file_get_contents($link);
	$DOMItem = new DOMDocument();
        $DOMItem->loadHTML($pageItem);
        $xpathItem = new DOMXPath($DOMItem);

	//La query per ottenere il titolo
        $queryTitle = '//div[@id="centerCol"]/div/div/h1/span[@id="ebooksProductTitle"]/text()';
        $entriesTitle = $xpathItem->query($queryTitle);

	//La variabile in cui mantengo il titolo trovato, sarebbe necessario estrarre il primo elemento da entriesTitle, ma al momento non ho capito come fare
	//quindi ho dovuto fare un for ignorante
	$t;
	foreach ($entriesTitle as $entryTitle)
        {
		$t = $entryTitle->nodeValue;
        }


	//Stessa storia per gli altri parametri; autore, prezzo, ecc.
        $a;

        $queryAutore = '//div[@id="centerCol"]/div[@id="booksTitle"]/div[@id="bylineInfo"]/span/span[@class="a-declarative"]/a[1]/text()';

        $entriesAutore = $xpathItem->query($queryAutore);

        foreach ($entriesAutore as $entryAutore)
        {
		$a = $entryAutore->nodeValue;
        }

	$p;

	$queryPrezzo = '//table[@class="a-lineitem a-spacing-micro"]//tr[@class="kindle-price"]/td[2]';

        $entriesPrezzo = $xpathItem->query($queryPrezzo);

        foreach ($entriesPrezzo as $entryPrezzo)
        {
                $p = $entryPrezzo->firstChild->nodeValue;
        }

        $e;

        $queryEditore = '//div[@id="detail_bullets_id"]/table//tr/td[@class="bucket"]/div/ul/li[4]/text()';

        $entriesEditore = $xpathItem->query($queryEditore);

        foreach ($entriesEditore as $entryEditore)
        {
                $e = $entryEditore->nodeValue;
        }

        $i;

	$queryImg = '//div[@id="leftCol"]/div[1]/div/div[2]/div/div/div/div/img/attribute::src';

        $entriesImg = $xpathItem->query($queryImg);

        foreach ($entriesImg as $entryImg)
        {
                $i = $entryImg->nodeValue;
        }

	//Creo un oggetto di tipo Book con i parametri trovati
	$b = new Book($t, $a, $p, $i, $link, $e);

	//Inserisco l'oggetto in un array
        array_push($booksFound, $b);
}

        return $booksFound;

//Controllo i parametri di ogni libro
//foreach ($books as $book)
//        $book->call();
}
?>
