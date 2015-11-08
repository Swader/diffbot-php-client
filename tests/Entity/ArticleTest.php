<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Article;
use Swader\Diffbot\Test\ResponseProvider;

class ArticleTest extends ResponseProvider
{
    protected static $staticFiles = [
        'Articles/diffbot-sitepoint-basic.json',
        // http%3A%2F%2Fwww.sitepoint.com%2Fdiffbot-crawling-visual-machine-learning
        'Articles/diffbot-sitepoint-extended.json',
        'Articles/apple-watch-verge-basic.json',
        // http%3A%2F%2Fwww.theverge.com%2Fa%2Fapple-watch-review
        'Articles/apple-watch-verge-extended.json',
        'Articles/15-11-07/diffbot-sitepoint-basic.json',
    ];

    /**
     * @dataProvider returnFiles
     * @param $file
     */
    public function testType($file)
    {
        /** @var Article $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals('article', $entity->getType());
        }
    }

    public function textProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 9897],
            ['Articles/diffbot-sitepoint-extended.json', 9897],
            ['Articles/apple-watch-verge-basic.json', 38541],
            ['Articles/apple-watch-verge-extended.json', 38541]
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @dataProvider textProvider
     */
    public function testText($file, $articles)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i],
                strlen($entity->getText()));
        }
    }

    public function htmlProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 14141],
            ['Articles/diffbot-sitepoint-extended.json', 14141],
            ['Articles/apple-watch-verge-basic.json', 42044],
            ['Articles/apple-watch-verge-extended.json', 42044]
        ];
    }


    /**
     * @param $file
     * @param $articles
     * @dataProvider htmlProvider
     */
    public function testHtml($file, $articles)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i],
                strlen($entity->getHtml()));
        }
    }

    public function dateProvider()
    {
        return [
            [
                'Articles/diffbot-sitepoint-basic.json',
                "Sun, 27 Jul 2014 00:00:00 GMT",
                2014
            ],
            [
                'Articles/diffbot-sitepoint-extended.json',
                "Sun, 27 Jul 2014 00:00:00 GMT",
                2014
            ],
            [
                'Articles/apple-watch-verge-basic.json',
                "Wed, 08 Apr 2015 00:00:00 GMT",
                2015
            ],
            [
                'Articles/apple-watch-verge-extended.json',
                "Wed, 08 Apr 2015 00:00:00 GMT",
                2015
            ]
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @dataProvider dateProvider
     */
    public function testDate($file, $articles, $year)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i], $entity->getDate());
            if (class_exists('\Carbon\Carbon')) {
                $this->assertEquals($year, $entity->getDate()->year);
            }
        }
    }

    public function authorProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', "Bruno Skvorc", "http://www.sitepoint.com/author/bskvorc/"],
            ['Articles/diffbot-sitepoint-extended.json', "Bruno Skvorc", "http://www.sitepoint.com/author/bskvorc/"],
            ['Articles/apple-watch-verge-basic.json', null, null],
            // Diffbot failed to detect this on April 12th
            ['Articles/apple-watch-verge-extended.json', null, null]
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @param $url
     * @dataProvider authorProvider
     */
    public function testAuthor($file, $articles, $url)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        $url = (is_array($url)) ? $url : [$url];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i], $entity->getAuthor());
            $this->assertEquals($url[$i], $entity->getAuthorUrl());
        }
    }

    public function pagesProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 1, []],
            ['Articles/diffbot-sitepoint-extended.json', 1, []],
            ['Articles/apple-watch-verge-basic.json', 1, []],
            ['Articles/apple-watch-verge-extended.json', 1, []]
        ];
    }

    /**
     * @param $file
     * @param $articlesNumPages
     * @param $articlesNextPages
     * @dataProvider pagesProvider
     */
    public function testPages($file, $articlesNumPages, $articlesNextPages)
    {
        $articlesNumPages = (is_array($articlesNumPages) && !empty($articlesNumPages))
            ? $articlesNumPages : [$articlesNumPages];
        $articlesNextPages = (is_array($articlesNextPages) && !empty($articlesNextPages))
            ? $articlesNextPages : [$articlesNextPages];

        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articlesNextPages[$i],
                $entity->getNextPages());
            $this->assertEquals($articlesNumPages[$i], $entity->getNumPages());
        }
    }

    public function mediaCountProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 9, 0],
            ['Articles/diffbot-sitepoint-extended.json', 9, 0],
            ['Articles/apple-watch-verge-basic.json', 1, 3],
            ['Articles/apple-watch-verge-extended.json', 1, 3]
        ];
    }

    /**
     * @param $file
     * @param $imagesNum
     * @param $videosNum
     * @dataProvider mediaCountProvider
     */
    public function testMedia($file, $imagesNum, $videosNum)
    {
        $imagesNum = (array)$imagesNum;
        $videosNum = (array)$videosNum;

        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($imagesNum[$i], count($entity->getImages()));
            $this->assertEquals($videosNum[$i], count($entity->getVideos()));
        }
    }

    public function sentimentProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', null],
            ['Articles/diffbot-sitepoint-extended.json', -0.0979036235342053],
            ['Articles/apple-watch-verge-basic.json', null],
            ['Articles/apple-watch-verge-extended.json', 0.19406914587488783]
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @dataProvider sentimentProvider
     */
    public function testSentiment($file, $articles)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i], $entity->getSentiment());
        }
    }

    public function tagsCountProvider() {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 5],
            ['Articles/diffbot-sitepoint-extended.json', 5],
            ['Articles/apple-watch-verge-basic.json', 5],
            ['Articles/apple-watch-verge-extended.json', 5]
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @dataProvider tagsCountProvider
     */
    public function testTagsCount($file, $articles)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($articles[$i], count($entity->getTags()));
            if ($articles[$i] > 0) {
                $tag = $entity->getTags()[0];
                $this->assertArrayHasKey('id', $tag);
                $this->assertArrayHasKey('prevalence', $tag);
                $this->assertArrayHasKey('uri', $tag);
                $this->assertArrayHasKey('count', $tag);
                $this->assertArrayHasKey('label', $tag);
                $this->assertArrayHasKey('type', $tag);
            }
        }
    }

    public function discussionDetailsProvider()
    {
        return [
            ['Articles/diffbot-sitepoint-basic.json', 'Swader\Diffbot\Entity\Discussion'],
        ];
    }

    /**
     * @param $file
     * @param $articles
     * @dataProvider discussionDetailsProvider
     */
    public function testDiscussion($file, $articles)
    {
        $articles = (is_array($articles)) ? $articles : [$articles];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            if ($articles[$i] == null) {
                $this->assertEquals(null, $entity->getDiscussion());
            } else {
                $this->assertInstanceOf($articles[$i],
                    $entity->getDiscussion());
            }
        }
    }

    public function siteNameProvider()
    {
        return [
            ['Articles/15-11-07/diffbot-sitepoint-basic.json', 'SitePoint'],
        ];
    }

    /**
     * @dataProvider siteNameProvider
     * @param $file
     * @param $value1
     */
    public function testSiteName($file, $value1)
    {
        $value1 = (is_array($value1)) ? $value1 : [$value1];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1[$i], $entity->getSiteName());
        }
    }

    public function publisherCountryProvider()
    {
        return [
            ['Articles/15-11-07/diffbot-sitepoint-basic.json', 'Australia'],
        ];
    }

    /**
     * @dataProvider publisherCountryProvider
     * @param $file
     * @param $value1
     */
    public function testPublisherCountry($file, $value1)
    {
        $value1 = (is_array($value1)) ? $value1 : [$value1];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1[$i], $entity->getPublisherCountry());
        }
    }

    public function publisherRegionProvider()
    {
        return [
            ['Articles/15-11-07/diffbot-sitepoint-basic.json', 'Australia and New Zealand'],
        ];
    }

    /**
     * @dataProvider publisherRegionProvider
     * @param $file
     * @param $value1
     */
    public function testPublisherRegion($file, $value1)
    {
        $value1 = (is_array($value1)) ? $value1 : [$value1];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1[$i], $entity->getPublisherRegion());
        }
    }

    public function estimatedDateProvider()
    {
        return [
            ['Articles/15-11-07/diffbot-sitepoint-basic.json', 'Sun, 27 Jul 2014 00:00:00 GMT', 2014],
        ];
    }

    /**
     * @dataProvider estimatedDateProvider
     * @param $file
     * @param $value1
     */
    public function testEstimatedDate($file, $value1, $value2)
    {
        $value1 = (is_array($value1)) ? $value1 : [$value1];
        /** @var Article $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($value1[$i], $entity->getEstimatedDate());
            if (class_exists('\Carbon\Carbon')) {
                $this->assertEquals($value2, $entity->getDate()->year);
            }
        }
    }

}
