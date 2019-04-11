# Adbis

- [Adbis](#adbis)
  - [Sources](#sources)
    - [Disclaimer](#disclaimer)
  - [Scraping](#scraping)
  - [Similarity metric](#similarity-metric)
  - [Technologies](#technologies)
  - [Installation](#installation)
  - [Credits](#credits)

Academic project for [web data integration](https://corsi.unisa.it/informatica-magistrale/didattica/insegnamenti?anno=2017&id=507522) course held by Prof. [G. Costagliola](https://rubrica.unisa.it/persone?matricola=001602) at the [Dipartimento di Informatica](http://www.di.unisa.it/) ('Department of Computer Science') of the University of Salerno.

**Adbis** is an *ebook and audiobook aggregator* that offers to its users the chance to buy books from several e-commerce web sites by just making their queries to a single web site.

## Sources

The available sources are the following ones:

- [Amazon](http://www.amazon.it/), [Kobo](http://www.kobo.com/) and [Google Books](https://play.google.com/store/books) (via API) for *ebooks*;
- [Audible](http://www.audible.it/) and [ilNarratore](https://www.ilnarratore.com/it/) for *audiobooks*;
- [QLibri](http://www.qlibri.it/) for *reviews*.

### Disclaimer

Sources styles may vary without further notice, causing the application to stop working as expected anytime.

## Scraping

Adbis architecture is based on a mediator among the above-mentioned sources. Previously retrieved results are stored into a database acting like a cache.

Apart from Google Play Books exposing an API, the sources required a scraping activity to retrieve their data; scraping classes have been implemented as a hierarchy, in order to gather common methods into the abstract superclass and specializing the type of items to scrape within the subclasses.

According to this scheme, a ``Scraper`` abstract class is superclass of ``BookScraper``, ``AudiobookScraper`` and ``ReviewScraper`` subclasses.

Every scraper connects to search pages via *cURL*; resulting pages are scraped by *XPath queries*, stored into source wrappers; extracted string data are checked in order to return valid results and a new entity is at last built and added into a set returned to wrappers which return it to mediator.

## Similarity metric

To determine whether a result was similar to user queried keyword, we implemented a similarity metric based on [Jaccard index](https://en.wikipedia.org/wiki/Jaccard_index) which is a value in ``[0, 1]`` range that express how much similar two strings are.

The basic algorithm is divided into following steps:

1. Tokenize both ``keyword`` and ``target`` strings;
2. Remove stop words from keyword set ``K`` and target set ``T``;
3. Calculate Jaccard index over ``K`` and ``T``:
   - if ``J(K, T)`` is greater or equal to ``0.5`` then ``keyword`` and ``target`` strings are similar
   - else check whether set ``K`` is contained into set ``T`` or vice versa: if there's containment of one of them into the other one, consider ``keyword`` and ``target`` strings similar.

## Technologies

Backend has been written in object-oriented **PHP** 7; to cache data about previous search results, **MySQL** RDBMS has been used; front-end interface has been developed with **Bootstrap**.

Dependencies for PHP have been managed by **Composer** while for JavaScript **NPM** was used.

## Installation

After cloning or downloading the repository or a release, make sure to run the following commands (``composer`` and ``npm`` have to be installed):

- in project root ``composer install``
- in ``view`` subfolder ``npm install`` (dependencies should be installed anyway, despite security warnings)

A web server has to be configured in order to properly use routing functionality (PHP integrated development server isn't enough for that, please rely on Apache server or nginx).

Adbis has been developed on Apache server, properly configured to support PHP; the following alias configuration has been specified to connect to it by ```http:\\localhost:8080\adbis\``` URL:

```apache
Alias /adbis "<parentDir>/adbis/"
<Directory "<parentDir>/adbis">
Options Indexes FollowSymLinks MultiViews ExecCGI
    AllowOverride All
        Require all granted
</Directory>
```

Also make sure that ```mod_rewrite``` module is enabled.

## Credits

Adbis authors are Antonio Addeo ([**@AddeusExMachina**](https://github.com/AddeusExMachina)) and Simone Bisogno ([**@bissim**](https://github.com/bissim)).
