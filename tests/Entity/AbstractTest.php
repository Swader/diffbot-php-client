<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Test\ResponseProvider;
use Swader\Diffbot\Traits\StandardEntity;

class AbstractTest extends ResponseProvider
{

    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Products/dogbrush.json'
    ];

    protected function ei($file)
    {
        $ef = new Entity();
        return $ef->createAppropriateIterator($this->prepareResponses()[$file]);
    }

    public function returnFiles()
    {
        $files = [];
        foreach ($this->files as $file) {
            $files[] = [$file];
        }
        return $files;
    }

    public function queryStringProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider queryStringProvider
     */
    public function testQueryString($file, $querystring)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($querystring, $entity->getQueryString());
        }
    }

    public function linksProvider()
    {
        return [
            ['Products/dogbrush.json', 0] // Zero because this is the basic request - without "links" field
        ];
    }

    /**
     * @dataProvider linksProvider
     */
    public function testLinks($file, $linksCount)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($linksCount, count($entity->getLinks()));
        }
    }

    public function breadcrumbProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                [
                    "Home",
                    "Dog",
                    "Supplies & Training",
                    "Grooming Supplies"
                ]
            ]
        ];
    }

    /**
     * @dataProvider breadcrumbProvider
     */
    public function testBreadcrumb($file, $bcTitles)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $breadCrumbTitles = [];
            foreach ($entity->getBreadcrumb() as $bc) {
                $breadCrumbTitles[] = $bc['name'];
            }
            $this->assertEquals($bcTitles, $breadCrumbTitles);
        }
    }

    public function metaProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                [
                    "title" => "Grreat Choice® Soft Slicker Dog Brush | Brushes, Combs & Blow Dryers | PetSmart",
                    "keywords" => "Grreat Choice® Soft Slicker Dog Brush | Brushes, Combs & Blow Dryers | PetSmart",
                    "description" => "Grreat Choice® Soft Slicker Dog Brush | ",
                    "format-detection" => "telephone=yes"
                ]
            ]
        ];
    }

    /**
     * @dataProvider metaProvider
     */
    public function testMeta($file, $meta)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($meta, $entity->getMeta());
        }
    }

    public function languageProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                "en"
            ]
        ];
    }

    /**
     * @dataProvider languageProvider
     */
    public function testLanguage($file, $language)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($language, $entity->getHumanLanguage());
        }
    }

    public function pageUrlProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                "http://www.petsmart.com/dog/grooming-supplies/grreat-choice-soft-slicker-dog-brush-zid36-12094/cat-36-catid-100016"
            ]
        ];
    }

    /**
     * @dataProvider pageUrlProvider
     */
    public function testPageUrl($file, $url)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($url, $entity->getPageUrl());
        }
    }

    public function resolvedUrlProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                "http://www.petsmart.com/dog/grooming-supplies/grreat-choice-soft-slicker-dog-brush-zid36-12094/cat-36-catid-100016"
            ]
        ];
    }

    /**
     * @dataProvider resolvedUrlProvider
     */
    public function testResolvedUrl($file, $url)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($url, $entity->getResolvedPageUrl());
        }
    }

    public function titleProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                "Grreat Choice® Soft Slicker Dog Brush"
            ]
        ];
    }

    /**
     * @dataProvider titleProvider
     */
    public function testTitle($file, $title)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($title, $entity->getTitle());
        }
    }

    public function diffbotUriProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                "product|3|892759351"
            ]
        ];
    }

    /**
     * @dataProvider diffbotUriProvider
     */
    public function testDiffbotUri($file, $uri)
    {
        /** @var StandardEntity $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($uri, $entity->getDiffbotUri());
        }
    }

    public function fileProvider()
    {
        return [
            [
                'Products/dogbrush.json', ["length" => 15, ["availability", "productId", "images"]]
            ]
        ];
    }

    /**
     * @dataProvider fileProvider
     */
    public function testData($file, $testParams)
    {
        /** @var \Swader\Diffbot\Abstracts\Entity $entity */
        foreach ($this->ei($file) as $entity) {
            $data = $entity->getData();
            if (isset($testParams["length"])) {
                $this->assertEquals($testParams["length"], count($data));
            }
            if (isset($testParams[1]) && is_array($testParams[1])) {
                foreach($testParams[1] as $key) {
                    $this->assertArrayHasKey($key, $data);
                }
            }
        }
    }

}
