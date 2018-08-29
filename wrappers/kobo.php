<?php

namespace wrappers;

require '..\model\Book.php';

use \model\Book;
use \DOMDocument;
use \DOMXPath;

function getKoboBooks($keyword)
{
	$koboURL = 'https://www.kobo.com/it/it/search?Query=';
	$keyword = str_replace(" ", "+", $keyword);
	$urlSearch = $koboURL . $keyword . '&fclanguages=it';

	$page = file_get_contents($urlSearch);

	$dom = new DOMDocument;
	$dom->loadHTML($page);
	$xpath = new DOMXPath($dom);

	$query = '//div[@class="item-info"]/p[@class="title product-field"]/a/attribute::href';
	$entries = $xpath->query($query);

	//echo "Trovati: " . $entriesLink->length . "<hr>";

	$links = array();

	foreach ($entries as $entryLink)
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
		$page = file_get_contents($link);

		$dom = new DOMDocument();
		$dom->loadHTML($page);
		$xpath = new DOMXPath($dom);

		$query = '//div[@class="item-info"]/h1/span/text()';
		$entries = $xpath->query($query);

		//
		$t = $entries[0]->nodeValue;
		// foreach ($entries as $entryTitle)
		// {
		// 	$t = $entryTitle->nodeValue;
		// }

		$query = '//div[@class="item-info"]/div/h2/span/span[@class="visible-contributors"]/a[1]/text()';
		$entries = $xpath->query($query);

		//
		$a = $entries[0]->nodeValue;
		foreach ($entriesAutore as $entryAutore)
		{
			$a = $entryAutore->nodeValue;
		}

		$query = '//div[@class="primary-right-container"]/div/div/div/div/div/span/text()';
		$entries = $xpath->query($query);

		//
		$p = $entries[0]->nodeValue;
		foreach ($entries as $entryPrezzo)
		{
			$p = $entryPrezzo->nodeValue;
		}
		$p = \floatval($p);

		/*
		// query for number of pages
		$query = '//section[@class="visible"/div/div/div/div[@class="stat-desc"]/strong/text()';
		$query = '//div[@class="support title-widget"]/div/section';
		$entries = $xpath->query($query);

		echo "numpag: " . $entries->length . "<br />";

		echo "numPages: $entries[0]->nodeValue<br />"
		// foreach ($entries as $entryNumPagine)
		// {
		// 	echo "numPagine: " . $entryNumPagine->nodeValue . "<br />";
		// }
		*/

		$query = '//div[@class="BookItemDetailSecondaryMetadataWidget"]/div/div/div/ul/li/a[@class="description-anchor"]/span/text()';
		$entries = $xpath->query($query);

		$e = $entries[0]->nodeValue;
		// foreach ($entriesEditore as $entryEditore)
		// {
		// 	$e = $entryEditore->nodeValue;
		// }

		$query = '//div[@class="primary-left-container"]/div/div/div/div/div/img/attribute::src';
		$entries = $xpath->query($query);

		//
		$i = $entries[0]->nodeValue;
		// foreach ($entriesImg as $entryImg)
		// {
		// 	$i = "https:" . $entryImg->firstChild->nodeValue;
		// }

		$b = new Book($t, $a, $p, $i, $link, $e);
		array_push($booksFound, $b);
	}

	return $booksFound;
}

//$books = searchBooks('harry potter');

//foreach ($books as $book)
//	print $book;
