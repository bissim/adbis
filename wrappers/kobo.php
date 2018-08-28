<?php

class Book
{
        private $titolo;
        private $autore;
        private $prezzo;
        private $img;
        private $link;
	private $editore;

        public function __construct($t, $a, $p, $i, $l, $e)
        {
        $this->titolo = $t;
	$this->autore = $a;
	$this->prezzo = $p;
	$this->img = $i;
	$this->link = $l;
	$this->editore = $e;
	}

        public function call()
        {
	echo "titolo: " . ($this->titolo) . ",<br>autore: " . ($this->autore) . ",<br>prezzo: " . ($this->prezzo) .
                ",<br>immmagine: " . ($this->img) . ",<br>link: " . ($this->link). ",<br>editore: " . ($this->editore) . "<hr>";
        }

}

function getKoboBooks($keyword)
{

$urlSearch = str_replace(" ", "+", "https://www.kobo.com/it/it/search?Query=" . $keyword . "&fclanguages=it");

$page = file_get_contents($urlSearch);

$DOM = new DOMDocument;

$DOM->loadHTML($page);

$xpath = new DOMXPath($DOM);

$queryLink = '//div[@class="item-info"]/p[@class="title product-field"]/a/attribute::href';

$entriesLink = $xpath->query($queryLink);

//echo "Trovati: " . $entriesLink->length . "<hr>";

$links = array();

foreach ($entriesLink as $entryLink)
{
        $l =  $entryLink->firstChild->nodeValue;
        array_push($links, $l);
}

$books = searchBooks($links);

return $books;

//foreach ($books as $book)
//	$book->call();
}

function searchBooks($links)
{

$booksFound = array();

foreach ($links as $link)
{
	$pageItem = file_get_contents($link);

	$DOMItem = new DOMDocument();

	$DOMItem->loadHTML($pageItem);

	$xpathItem = new DOMXPath($DOMItem);

	$queryTitle = '//div[@class="item-info"]/h1/span/text()';

	$entriesTitle = $xpathItem->query($queryTitle);

	$t;

	foreach ($entriesTitle as $entryTitle)
	{
		$t = $entryTitle->nodeValue;
	}

	$a;

        $queryAutore = '//div[@class="item-info"]/div/h2/span/span[@class="visible-contributors"]/a[1]/text()';

        $entriesAutore = $xpathItem->query($queryAutore);

        foreach ($entriesAutore as $entryAutore)
        {
		$a = $entryAutore->nodeValue;
	}

	$p;

        $queryPrezzo = '//div[@class="primary-right-container"]/div/div/div/div/div/span/text()';

        $entriesPrezzo = $xpathItem->query($queryPrezzo);

        foreach ($entriesPrezzo as $entryPrezzo)
	{
		$p = $entryPrezzo->nodeValue;
	}

/*	$queryNumPagine = '//section[@class="visible"/div/div/div/div[@class="stat-desc"]/strong/text()';

	$queryNumPagine = '//div[@class="support title-widget"]/div/section';

        $entriesNumPagine = $xpathItem->query($queryNumPagine);

	echo "numpag: " . $entriesNumPagine->length . "<br>";

        foreach ($entriesNumPagine as $entryNumPagine)
                echo "numPagine: " . $entryNumPagine->nodeValue . "<br>";
*/
	$e;

        $queryEditore = '//div[@class="BookItemDetailSecondaryMetadataWidget"]/div/div/div/ul/li/a[@class="description-anchor"]/span/text()';

        $entriesEditore = $xpathItem->query($queryEditore);

        foreach ($entriesEditore as $entryEditore)
        {
		$e = $entryEditore->nodeValue;
	}

	$i;

	$queryImg = '//div[@class="primary-left-container"]/div/div/div/div/div/img/attribute::src';

        $entriesImg = $xpathItem->query($queryImg);

        foreach ($entriesImg as $entryImg)
        {
		$i = "https:" . $entryImg->firstChild->nodeValue;
	}

	$b = new Book($t, $a, $p, $i, $link, $e);

	array_push($booksFound, $b);
}

	return $booksFound;

//foreach ($books as $book)
//	$book->call();
}

//searchBooks("harry potter");
?>
