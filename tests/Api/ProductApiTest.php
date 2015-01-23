<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\Product;

class ProductApiTest extends \PHPUnit_Framework_TestCase
{

    protected $validMock;

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
        $diffbot = $this->getValidDiffbotInstance();

        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($this->getValidMock());

        $diffbot->setHttpClient($fakeClient);
        $diffbot->setEntityFactory();

        $api = $diffbot->createProductAPI('https://dogbrush-mock.com');

        /** @var Product $product */
        $product = $api->call();

        $targetTitle = 'Grreat Choice® Soft Slicker Dog Brush';
        $this->assertEquals($targetTitle, $product->getTitle());
    }

}
