<?php

// L'URL della pagina dove appare il catalogo degli ebook cercati in base a una keyword
$amazonURL = 'https://www.amazon.it/s/ref=nb_sb_noss?__mk_it_IT=%C3%85M%C3%85%C5%BD%C3%95%C3%91&url=node%3D827182031&field-keywords=';
$keyword = 'dan brown';
$keyword = str_replace(' ', '+', $keyword);
$urlSearch = $amazonURL . $keyword;

echo 'Link ricerca: ' . $urlSearch . '<br>';

// La pagine viene caricate
$page = file_get_contents($urlSearch);

// Si crea un DOM della pagina e un XPath
$dom = new DOMDocument;
$dom->loadHTML($page);
$xpath = new DOMXPath($dom);

// La query per ottenere il link di ogni ebook dal catalogo mostrato

//$queryLink = '//div[@class="a-row a-spacing-small"]/div/a/attribute::href';

$queryLink = '//div[@class="a-fixed-left-grid-col a-col-right"]/div/div/a/attribute::href';

// Si estraggono i link
$entriesLink = $xpath->query($queryLink);

echo 'Link trovati: ' .  $entriesLink->length . '<br>';

$links = array();

foreach ($entriesLink as $entryLink)
{
	$l = $entryLink->nodeValue;
	array_push($links, $l);
}

foreach ($links as $link)
	echo $link . '<br>';

echo '<hr>';

$queryTitle = '//div[@class="a-fixed-left-grid-col a-col-right"]/div/div/a/h2/text()';

$entriesTitle = $xpath->query($queryTitle);

echo 'Titoli trovati: ' . $entriesTitle->length . '<br>';

$titles = array();

foreach ($entriesTitle as $entryTitle)
{
        $t = $entryTitle->nodeValue;
        array_push($titles, $t);
}

foreach ($titles as $title)
        echo $title . '<br>';

echo '<hr>';


$queryAuthor = '//div[@class="a-row a-spacing-small"]/div[2]/span[2]/a/text()';

$entriesAuthor = $xpath->query($queryAuthor);

echo 'Autori trovati: ' . $entriesAuthor->length . '<br>';

$authors = array();

foreach ($entriesAuthor as $entryAuthor)
{
        $a = $entryAuthor->nodeValue;
        array_push($authors, $a);
}

foreach ($authors as $author)
        echo $author . '<br>';

echo '<hr>';

$queryImg = '//div[@class="s-item-container"]/div/div/div/div/div/a/img/attribute::src';

$entriesImg = $xpath->query($queryImg);

echo 'Immagini trovate: ' . $entriesImg->length . '<br>';

$images = array();

foreach ($entriesImg as $entryImg)
{
        $i = $entryImg->nodeValue;
        array_push($images, $i);
}

foreach ($images as $image)
        echo $image . '<br>';

echo '<hr>';

$queryPrice = '//div[@class="s-item-container"]/div/div/div/div/div/div/a/span[2]/text()';


$entriesPrice = $xpath->query($queryPrice);

echo 'Prezzi trovate: ' . $entriesPrice->length . '<br>';

$prices = array();

foreach ($entriesPrice as $entryPrice)
{
        $p = $entryPrice->nodeValue;
        array_push($prices, $p);
}

foreach ($prices as $price)
        echo $price . '<br>';

echo '<hr>';

?>
