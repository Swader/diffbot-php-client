#Changelog
All notable changes will be documented in this file

## 0.4.2

- [Internal] Fixed issue #14 - EntityIterator cursor bug

## 0.4.1

- [Internal] Added ArrayAccess to EntityIterator

## 0.4 - June 11th, 2015

- [Feature] Added Search API
- [Feature] Added SearchInfo: apart from Entites in a regular EntityIterator as usual, the Search API returns a SearchInfo object, too. See README.

## 0.3 - May 17th, 2015

### Internal changes

- [Internal] DiffbotAware trait now responsible for registering Diffbot parent in children
- [BC Break, Internal] PHP 5.6 is now required (`...` operator)
- [Internal] Updated all API calls to HTTPS

### Features

- [Feature] Implemented Crawlbot API, added usage example to README
    - [Feature] Added `Job` abstract entity with `JobCrawl` and `JobBulk` derivations. A `Job` is either a [Bulk API job](https://www.diffbot.com/dev/docs/bulk) or a [Crawl job](https://www.diffbot.com/dev/docs/crawl). A collection of jobs is the result of a Crawl or Bulk API call. When job name is provided, a max of one item is present in the collection.
    
### Bugs

- [Bug] Fixed [#1](https://github.com/Swader/diffbot-php-client/issues/1)

### Meta

- [Repository] Added TODOs as issues in repo, linked to relevant ones in [TODO file](TODO.md).
- [CI] Stopped testing for 5.4 and 5.5, updated Travis and Scrutinizer file to take this into account
- [Tests] Fully tested Crawlbot implementation

## 0.2 - May 2nd, 2015

- added Discussion API
- added basic Custom API - returns Wildcards by default
- Discussion API is now returned as child of Article / Product, too
- minor change in how URLs are built - trailing slash no longer enforced after base API URL
- entity properties can be accessed directly from EntityIterator now, no need to actually iterate. This forwards the property call to the first entity in the set.

## 0.1.3 - April 21st, 2015

- new Scrutinizer settings
- added PHP 7 runtime to Travis
- minor fixes in code and internal API
- fixed homepage link in composer.json

## 0.1.2 - April 20th, 2015

Fixed some type hints as pointed out by Scrutinizer.

## 0.1.1 - April 20th, 2015

Minor fixes on composer.json, README, and added to Travis

## 0.1 - April 20th, 2015

Initial public release.