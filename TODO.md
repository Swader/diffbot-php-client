# Active TODOs

Active todos, ordered by priority

## High

- [implement Bulk Processing Support](https://github.com/Swader/diffbot-php-client/issues/3)

## Medium

- [add streaming to Crawlbot](https://github.com/Swader/diffbot-php-client/issues/5)
- [implement Video API](https://github.com/Swader/diffbot-php-client/issues/6) (currently beta)
- [implement Webhook](https://github.com/Swader/diffbot-php-client/issues/7) for Bulk / Crawlbot completion
- look into adding async support via Guzzle
- consider alternative solution to 'crawl' setting in Api abstract ([#8](https://github.com/Swader/diffbot-php-client/issues/8)).
- API docs needed ([#9](https://github.com/Swader/diffbot-php-client/issues/3))

## Low

- see what can be done with the [URL report](https://www.diffbot.com/dev/docs/crawl/) - some implementation options?
- add more usage examples
- work on PhpDoc consistency ($param type vs type $param)
- get more mock responses and test against them
- ~~write example with custom EntityIterator (different Entity set for different API) and custom Entity (i.e. authorProfile, which parses some of the data and prepares for further use)~~ done via [this](http://www.sitepoint.com/powerful-custom-entities-with-the-diffbot-php-client)
