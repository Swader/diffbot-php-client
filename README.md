[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Swader/diffbot-php-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Swader/diffbot-php-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Swader/diffbot-php-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Swader/diffbot-php-client/?branch=master)
[![Build Status](https://travis-ci.org/Swader/diffbot-php-client.svg?branch=master)](https://travis-ci.org/Swader/diffbot-php-client)

# Diffbot PHP API Wrapper

This package is a slightly overengineered Diffbot API wrapper. It uses Guzzle to make API calls. To learn more about Diffbot see [here](http://www.sitepoint.com/tag/diffbot/) and [their homepage](http://diffbot.com).
Right now it only supports Analyze, Product, Image, Discussion and Article APIs, but can also accommodate Custom APIs. Crawlbot, Search and Video API support coming soon.

## Requirements

Minimum PHP 5.4 because Guzzle needs it.

## Install

Via Composer

```bash
composer require swader/diffbot-php-client
```

## Usage - simple

Simplest possible use case:

```php
$diffbot = new Diffbot('my_token');
$url = 'http://www.sitepoint.com/diffbot-crawling-visual-machine-learning/';
$articleApi = $diffbot->createArticleAPI($url);

echo $articleApi->call()->author; // prints out "Bruno Skvorc"
```

That's it, this is all you need to get started.

## Usage - advanced

Full API reference manual in progress, but the instructions below should do for now - the library was designed with brutal UX simplicity in mind.

### Setup

To begin, always create a Diffbot instance. A Diffbot instance will spawn API instances.
To get your token, sign up at http://diffbot.com

```php
$diffbot = new Diffbot('my_token');
```

### Pick API

Then, pick an API.

Currently available [*automatic*](http://www.diffbot.com/products/automatic/) APIs are:

- [product](http://www.diffbot.com/products/automatic/product/) (crawls products and their reviews, if available)
- [article](http://www.diffbot.com/products/automatic/article/) (crawls news posts, blogs, etc, with comments if available)
- [image](http://www.diffbot.com/products/automatic/image/) (fetches information about images - useful for 500px, Flickr etc). The Image API can return several images - depending on how many are on the page being crawled.
- [discussion](http://www.diffbot.com/products/automatic/discussion/) (fetches discussion / review / comment threads - can be embedded in the Product or Article return data, too, if those contain any comments or discussions)
- [analyze](http://www.diffbot.com/products/automatic/analyze/) (combines all the above in that it automatically determines the right API for the URL and applies it)

Video is coming soon.

There is also a [Custom API](http://www.diffbot.com/products/custom/) like [this one](http://www.sitepoint.com/analyze-sitepoint-author-portfolios-diffbot/) - unless otherwise configured, they return instances of the Wildcard entity)

All APIs can also be tested on http://diffbot.com

The API you picked can be spawned through the main Diffbot instance:

```php
$api = $diffbot->createArticleAPI($url);
```

### API configuration

All APIs have some optional fields you can pass with parameters. For example, to extract the 'meta' values of the page alongside the normal data, call `setMeta`:

```php
$api->setMeta(true);
```

Some APIs have other flags that don't qualify as fields. For example, the Article API can be told to ignore Discussions (aka to not extract comments). This can speed up the fetching, because by default, it does look for them. The configuration methods all have the same format, though, so to accomplish this, just use `setDiscussion`:

```php
$api->setDiscussion(false);
```

All config methods are chainable:

```php
$api->setMeta(true)->setDiscussion(false);
```

For an overview of all the config fields and the values each API returns, see [here](https://www.diffbot.com/dev/docs).

### Calling

All API instances have the `call` method which returns a collection of results. The collection is iterable:

```php
$url = 'http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/';
$imageApi = $diffbot->createImageAPI($url);
/** @var Image $imageEntity */
foreach ($imageApi->call() as $imageEntity) {
    echo 'Image dimensions: ' . $imageEntity->getHeight() . ' x ' . $imageEntity->getWidth() . '<br>';
}

/* Output:
Image dimensions: 333 x 500
Image dimensions: 333 x 500
Image dimensions: 334 x 500
Image dimensions: 333 x 500
Image dimensions: 333 x 500
Image dimensions: 333 x 500
Image dimensions: 333 x 500
Image dimensions: 333 x 500
Image dimensions: 333 x 500
*/
```

In cases where only one entity is returned, like Article or Product, iterating works all the same, it just iterates through the one single elements. The return data is **always** a collection! 

However, for brevity, you can access properties directly on the collection, too.

```php
$articleApi = $diffbot->createArticleAPI('http://www.sitepoint.com/diffbot-crawling-visual-machine-learning/');
echo $articleApi->call()->author;
// or $articleApi->call()->getAuthor();
```

In this case, the collection applies the property call to the first element which, coincidentally, is also the only element. If you use this approach on the image collection above, the same thing happens - but the call is only applied to the first image entity in the collection.

### Just the URL, please

If you just want the final generated URL (for example, to paste into Postman Client or to test in the browser and get pure JSON), use `buildUrl`:

```php
$url = $articleApi->buildUrl();
```

You can continue regular API usage afterwards, which makes this very useful for logging, etc.

### Pure response

You can extract the pure, full Guzzle Response object from the returned data and then manipulate it as desired (maybe parsing it as JSON and processing it further on your own):

```php
$articleApi = $diffbot->createArticleAPI('http://www.sitepoint.com/diffbot-crawling-visual-machine-learning/');
$guzzleResponse = $articleApi->call()->getResponse();
```

Individual entities do not have access to the response - to fetch it, always fetch from their parent collection (the object that the `call()` method returns).

### Discussion and Post

The Discussion API returns some data about the discussion and contains another collection of Posts. A Post entity corresponds to a single review / comment / forum post, and is very similar in structure to the Article entity.

You can iterate through the posts as usual:

```php
$url = 'http://community.sitepoint.com/t/php7-resource-recap/174325/';
$discussion = $diffbot->createDiscussionAPI($url)->call();
/** @var Post $post */
foreach($discussion->getPosts() as $post) {
    echo 'Author: '.$post->getAuthor().'<br>';
}

/*
Output:

Author: swader
Author: TaylorRen
Author: s_molinari
Author: s_molinari
Author: swader
Author: s_molinari
Author: swader
Author: s_molinari
Author: swader
Author: s_molinari
Author: TomB
Author: s_molinari
Author: TomB
Author: Wolf_22
Author: swader
Author: swader
Author: s_molinari
*/
```

An Article or Product entity can contain a Discussion entity. Access it via `getDiscussion` on an Article or Product entity and use as usual (see above).

## Custom API

Used just like all others. There are only two differences:

1. When creating a Custom API call, you need to pass in the API name
2. It always returns Wildcard entities which are basically just value objects containing the returned data. They have `__call` and `__get` magic methods defined so their properties remain just as accessible as the other Entities', but without autocomplete.

The following is a usage example of my own custom API for author profiles at SitePoint:

```php
$diffbot = new Diffbot('brunoskvorc');
$customApi = $diffbot->createCustomAPI('http://sitepoint.com/author/bskvorc', 'authorFolioNew');

$return = $customApi->call();

foreach ($return as $wildcard) {
    dump($wildcard->getAuthor()); // Bruno Skvorc
    dump($wildcard->author); // Bruno Skvorc
}
```

Of course, you can easily extend the basic Custom API class and make your own, as well as add your own Entities that perfectly correspond to the returned data. This will all be covered in a tutorial in the near future.

## Testing

Just run PHPUnit in the root folder of the cloned project.
Some calls do require an internet connection (see `tests/Factory/EntityTest`).

```bash
phpunit
```

### Adding Entity tests

**I'll pay $10 for every new set of 5 Entity tests, submissions verified set per set - offer valid until I feel like there's enough use cases covered.** (a.k.a. don't submit 1500 of them at once, I can't pay that in one go).

If you would like to contribute by adding Entity tests, I suggest following this procedure:

1. Pick an API you would like to contribute a test for. E.g., Product API.
2. In a scratchpad like `index.php`, build the URL:

    ```php
    $diffbot = new Diffbot('my_token');
    $url = $diffbot
        ->createProductAPI('http://someurl.com')
        ->setMeta(true)
        ->...(insert other config methods here as desired)...
        ->buildUrl();
    echo $url;
    ```

3. Grab the URL and paste it into a REST client like Postman or into your browser. You'll get Diffbot's response back. Keep it open for reference.
4. Download this response, with headers, into a JSON file. Preferably into `tests/Mocks/Products/[date]/somefilename.json`, like the other tests are. This is easily accomplished by executing `curl -i "[url] > somefilename.json"` in the Terminal/Command Line.
5. Go into the appropriate tests folder. In this case, `tests/Entity` and open `ProductTest.php`. Notice how the file is added into the batch of files to be tested against. Every provider has it referenced, along with the value the method being tested should produce. Slowly go through every test method and add your file. Use the values in the JSON you got in step 3 to get the values.
6. Run `phpunit tests/Entity/ProductTest.php` to test just this file (much faster than entire suite). If OK, send PR :)

If you'd like to create your own Test classes, too, that's fine, no need to extend the ones that are included with the project. Apply the whole process just rather than extending the existing `ProductTest` class make a new one.

### Adding other tests

Other tests don't have specific instructions, contribute as you see fit. Just try to minimize actual remote calls - we're not testing the API itself (a.k.a. Diffbot), we're testing this library. If the library parses values accurately from an inaccurate API response because, for example, Diffbot is currently bugged, that's fine - the library works!

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details and [TODO](TODO.md) for ideas.

## Credits

- [Bruno Skvorc](https://github.com/swader)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.