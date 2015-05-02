<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Product;
use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Test\ResponseProvider;

class ProductTest extends ResponseProvider
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

    /**
     * @dataProvider returnFiles
     */
    public function testType($file)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals('product', $entity->getType());
        }
    }

    public function textProvider()
    {
        return [
            ['Products/dogbrush.json', 513]
        ];
    }

    /**
     * @dataProvider textProvider
     */
    public function testText($file, $textLength)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($textLength, strlen($entity->getText()));
        }
    }

    public function regularPriceProvider()
    {
        return [
            ['Products/dogbrush.json', "$4.99"]
        ];
    }

    /**
     * @dataProvider regularPriceProvider
     */
    public function testRegularPrice($file, $regularPrice)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($regularPrice, $entity->getRegularPrice());
        }
    }

    public function regularPriceDetailsProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                [
                    "amount" => 4.99,
                    "text" => "$4.99",
                    "symbol" => "$"
                ]
            ]
        ];
    }

    /**
     * @dataProvider regularPriceDetailsProvider
     */

    public function testRegularPriceDetails($file, $regularPriceDetails)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($regularPriceDetails,
                $entity->getRegularPriceDetails());
        }
    }

    public function shippingAmountProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider shippingAmountProvider
     */
    public function testShippingAmount($file, $shippingAmount)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($shippingAmount, $entity->getShippingAmount());
        }
    }

    public function saveAmountProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider saveAmountProvider
     */

    public function testSaveAmount($file, $saveAmount)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($saveAmount, $entity->getSaveAmount());
        }
    }

    public function saveAmountDetailsProvider()
    {
        return [
            ['Products/dogbrush.json', []]
        ];
    }

    /**
     * @dataProvider saveAmountDetailsProvider
     */
    public function testSaveAmountDetails($file, $saveAmountDetails)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($saveAmountDetails,
                $entity->getSaveAmountDetails());
        }
    }

    public function productIdProvider()
    {
        return [
            ['Products/dogbrush.json', "36-12094"]
        ];
    }

    /**
     * @dataProvider productIdProvider
     */
    public function testProductId($file, $productId)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($productId, $entity->getProductId());
        }
    }

    public function upcProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider upcProvider
     */
    public function testUpc($file, $upc)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($upc, $entity->getUpc());
        }
    }

    public function mpnProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider mpnProvider
     */
    public function testMpn($file, $mpn)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($mpn, $entity->getMpn());
        }
    }

    public function isbnProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider isbnProvider
     */
    public function testIsbn($file, $isbn)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($isbn, $entity->getIsbn());
        }
    }

    public function specsProvider()
    {
        return [
            ['Products/dogbrush.json', []]
        ];
    }

    /**
     * @dataProvider specsProvider
     */
    public function testSpecs($file, $specs)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($specs, $entity->getSpecs());
        }
    }

    public function imagesProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                [
                    [
                        "title" => "Roll over image to zoom",
                        "height" => 300,
                        "diffbotUri" => "image|3|107739285",
                        "naturalHeight" => 300,
                        "width" => 300,
                        "primary" => true,
                        "naturalWidth" => 300,
                        "url" => "http://petus.imageg.net/PETNA_36/pimg/pPETNA-5140457_main_t300x300.jpg",
                        "xpath" => "/html[1]/body[1]/div[3]/div[2]/div[3]/div[2]/div[1]/div[1]/img[1]"
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider imagesProvider
     */
    public function testImages($file, $images)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($images, $entity->getImages());
        }
    }

    public function prefixCodeProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider prefixCodeProvider
     */
    public function testPrefixCode($file, $prefixCode)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($prefixCode, $entity->getPrefixCode());
        }
    }

    public function productOriginProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider productOriginProvider
     */
    public function testProductOrigin($file, $productOrigin)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($productOrigin, $entity->getProductOrigin());
        }
    }

    public function priceRangeProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider priceRangeProvider
     */
    public function testPriceRange($file, $priceRange)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($priceRange, $entity->getPriceRange());
        }
    }

    public function quantityPricesProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider quantityPricesProvider
     */
    public function testQuantityPrices($file, $quantityPrices)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($quantityPrices, $entity->getQuantityPrices());
        }
    }

    public function availabilityProvider()
    {
        return [
            ['Products/dogbrush.json', true]
        ];
    }

    /**
     * @dataProvider availabilityProvider
     */
    public function testAvailability($file, $available)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($available, $entity->isAvailable());
        }
    }

    public function offerPriceProvider()
    {
        return [
            ['Products/dogbrush.json', "$4.99"]
        ];
    }

    /**
     * @dataProvider offerPriceProvider
     */
    public function testOfferPrice($file, $offerPrice)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($offerPrice, $entity->getOfferPrice());
        }
    }

    public function offerPriceDetailsProvider()
    {
        return [
            [
                'Products/dogbrush.json',
                [
                    "amount" => 4.99,
                    "text" => "$4.99",
                    "symbol" => "$"
                ]
            ]
        ];
    }

    /**
     * @dataProvider offerPriceDetailsProvider
     */
    public function testOfferPriceDetails($file, $offerPriceDetails)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($offerPriceDetails,
                $entity->getOfferPriceDetails());
        }
    }

    public function sizeProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider sizeProvider
     */
    public function testSize($file, $size)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($size, $entity->getSize());
        }
    }

    public function colorsProvider()
    {
        return [
            ['Products/dogbrush.json', null]
        ];
    }

    /**
     * @dataProvider colorsProvider
     */
    public function testColors($file, $colors)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($colors, $entity->getColors());
        }
    }

    public function brandProvider()
    {
        return [
            ['Products/dogbrush.json', "Grreat Choice"]
        ];
    }

    /**
     * @dataProvider brandProvider
     */
    public function testBrand($file, $brand)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($brand, $entity->getBrand());
        }
    }

    public function skuProvider()
    {
        return [
            ['Products/dogbrush.json', "36-12094"]
        ];
    }

    /**
     * @dataProvider skuProvider
     */
    public function testSku($file, $sku)
    {
        /** @var Product $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals($sku, $entity->getSku());
        }
    }

    public function discussionDetailsProvider()
    {
        return [
            ['Products/dogbrush.json', null],
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
        /** @var Product $entity */
        foreach ($this->ei($file) as $i => $entity) {
            if ($articles[$i] == null) {
                $this->assertEquals(null, $entity->getDiscussion());
            } else {
                $this->assertInstanceOf($articles[$i],
                    $entity->getDiscussion());
            }
        }
    }


}
