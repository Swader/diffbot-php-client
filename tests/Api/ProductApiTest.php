<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\Product;

class ProductApiTest extends \PHPUnit_Framework_TestCase
{

    protected $validMock;

    /**
     * @var \Swader\Diffbot\Api\Product
     */
    protected $apiWithMock;

    protected function setUp() {
        $diffbot = $this->getValidDiffbotInstance();
        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($this->getValidMock());

        $diffbot->setHttpClient($fakeClient);
        $diffbot->setEntityFactory();

        $this->apiWithMock = $diffbot->createProductAPI('https://dogbrush-mock.com');
    }

    protected function getValidDiffbotInstance()
    {
        return new Diffbot('demo');
    }

    protected function getValidMock(){
        if (!$this->validMock) {
            $this->validMock = new Mock(
                [file_get_contents(__DIR__.'/../Mocks/Products/dogbrush.json')]
            );
        }
        return $this->validMock;
    }

    public function testCall() {
        $products = $this->apiWithMock->call();

        foreach ($products as $product) {
            $this->assertInstanceOf('Swader\Diffbot\Entity\Product', $product);
        }
    }

    public function testBuildUrlNoCustomFields() {
        $url = $this
            ->apiWithMock
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/product?token=demo&url=https%3A%2F%2Fdogbrush-mock.com';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlMultipleCustomFields() {
        $url = $this
            ->apiWithMock
            ->setColors(true)
            ->setSize(true)
            ->setAvailability(true)
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/product?token=demo&url=https%3A%2F%2Fdogbrush-mock.com&fields=colors,size,availability';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlMultipleCustomFieldsAndOtherOptions() {
        $url = $this
            ->apiWithMock
            ->setColors(true)
            ->setSize(true)
            ->setAvailability(true)
            ->setDiscussion(false)
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/product?token=demo&url=https%3A%2F%2Fdogbrush-mock.com&fields=colors,size,availability&discussion=false';
        $this->assertEquals($expectedUrl, $url);
    }
}
