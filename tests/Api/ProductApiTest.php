<?php

namespace Swader\Diffbot\Test\Api;

use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Api\Product;

class ProductApiTest extends \PHPUnit_Framework_TestCase
{

    private function getValidDiffbotInstance()
    {
        return new Diffbot('demo');
    }

    private function getNewProductAPI()
    {
        return $this->getValidDiffbotInstance()
            ->createProductAPI('http://www.amazon.com/Oh-The-Places-Youll-Go/dp/0679805273/');
    }

    public function testCall() {
        $productApi = $this->getNewProductAPI();
        $productApi->call();
    }
}
