<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Diffbot;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    private $className = 'Swader\Diffbot\Abstracts\Api';
    private $testUrl = 'http://diffbot.com';

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function buildMock()
    {
        return $this->getMockForAbstractClass($this->className, [$this->testUrl]);
    }

    public function validTimeouts()
    {
        return [
            'zero' => [0],
            '1000' => [1000],
            '2000' => [2000],
            '3000' => [3000],
            '3 mil' => [3000000],
            '40 mil' => [40000000]
        ];
    }

    public function invalidTimeouts()
    {
        return [
            'negative_big' => [-298979879827],
            'negative_small' => [-4983],
            'string ' => ['abcef'],
            'empty string' => [''],
            'bool' => [false]
        ];
    }

    public function testSetEmptyTimeoutSuccess()
    {
        /** @var Api $mock */
        $mock = $this->buildMock();
        $mock->setTimeout();
    }

    /**
     * @dataProvider invalidTimeouts
     * @param $timeout mixed
     */
    public function testSetTimeoutInvalid($timeout)
    {
        /** @var Api $mock */
        $mock = $this->buildMock();
        $this->setExpectedException('InvalidArgumentException');
        $mock->setTimeout($timeout);
    }

    /**
     * @dataProvider validTimeouts
     * @param $timeout int
     */
    public function testSetTimeoutValid($timeout)
    {
        /** @var Api $mock */
        $mock = $this->buildMock();
        $mock->setTimeout($timeout);
    }

    public function validUrls()
    {
        return [
            ['http://google.com'],
            ['http://gigaom.com/cloud/silicon-valley-royalty-pony-up-2m-to-scale-diffbots-visual-learning-robot/'],
            ['http://techcrunch.com/2012/05/31/diffbot-raises-2-million-seed-round-for-web-content-extraction-technology/'],
            ['http://www.theverge.com/2012/5/31/3054444/diffbot-raises-2-million-apps-open-web'],
            ['http://venturebeat.com/2012/08/16/diffbot-api-links'],
            ['http://www.wired.co.uk/news/archive/2012-06/01/diffbot'],
            ['http://www.amazon.com/Oh-The-Places-Youll-Go/dp/0679805273/'],
            ['http://us.levi.com/product/index.jsp?productId=2076855'],
            ['http://www.petsmart.com/dog/grooming-supplies/grreat-choice-soft-slicker-dog-brush-zid36-12094/cat-36-catid-100016'],
            ['http://instagram.com/p/t879OvgvqS/'],
            ['http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/'],
            ['https://twitter.com/NASA/status/525397368116895744'],
            ['www.example.com'],
            ['example.com']
        ];
    }

    public function invalidUrls()
    {
        return [
            'bool' => [false],
            'null' => [null],
            'number' => [12345],
            'abc' => ['abc'],
            'misc_string' => ['35tugz---sdf----?//*****/*//*']
        ];
    }

    /**
     * @dataProvider validUrls
     * @param $url string
     */
    public function testValidUrls($url)
    {
        $mock = $this->getMockForAbstractClass($this->className, [$url]);
        $this->assertInstanceOf($this->className, $mock);
    }

    /**
     * @dataProvider invalidUrls
     * @param $url mixed
     */
    public function testInvalidUrls($url)
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->getMockForAbstractClass($this->className, [$url]);
    }

    protected function returnApis()
    {
        $diffbot = new Diffbot('demo');
        $demoUrl = 'https://www.google.com';
        return [
            'product' => $diffbot->createProductAPI($demoUrl),
            'analyze' => $diffbot->createAnalyzeAPI($demoUrl),
            'article' => $diffbot->createArticleAPI($demoUrl),
            'image' => $diffbot->createImageAPI($demoUrl)
        ];
    }

    public function validOptionalFieldValues()
    {
        return [
            [true],
            [false],
            [null]
        ];
    }

    public function invalidOptionalFieldValues()
    {
        return [
            'stringT' => ['true'],
            'stringF' => ['false'],
            [5],
            [0],
            [1],
            'empty' => [''],
            'operation' => [5 + 5],
            'string' => ['hello'],
            '1e-arrayT' => [[true]],
            '1e-arrayF' => [[false]],
            '2e-array_bool' => [[true, false]]
        ];
    }

    public function invalidFieldNames()
    {
        return [
            ['gibberish'],
            ['nonsense'],
            ['folly']
        ];
    }

    /**
     * @dataProvider validOptionalFieldValues
     * @param $fieldValue bool
     */
    public function testFieldSettersSuccess($fieldValue)
    {
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $fields = $api::getOptionalFields();
            foreach ($fields as $field) {
                $setterName = 'set' . ucfirst($field);
                $getterName = 'get' . ucfirst($field);
                $api->$setterName($fieldValue);
                $this->assertTrue(is_bool($api->$getterName()));
            }
        }
    }

    /**
     * @dataProvider invalidOptionalFieldValues
     * @param $fieldValue mixed
     */
    public function testFieldSettersFail($fieldValue)
    {
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $fields = $api::getOptionalFields();
            foreach ($fields as $field) {
                $setterName = 'set' . ucfirst($field);
                try {
                    $api->$setterName($fieldValue);
                } catch (\Exception $e) {
                    $this->assertInstanceOf('InvalidArgumentException', $e);
                    continue;
                }
                $message = 'API did not error when given wrong input into ' . $setterName . '. ';
                $message .= 'The input was: ' . print_r($fieldValue, true);
                $this->fail($message);
            }
        }
    }

    /**
     * @dataProvider invalidFieldNames
     * @param string $fieldName
     */
    public function testFieldFail($fieldName)
    {
        $getterName = 'get' . ucfirst($fieldName);
        $setterName = 'set' . ucfirst($fieldName);
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $e1 = false;
            try {
                $api->$getterName();
            } catch (\BadMethodCallException $e1) {
                // Exception should happen, all good
            }
            if (!$e1) {
                $this->fail('No exception raised when getting invalid field: ' . $fieldName);
            }

            $e2 = false;
            try {
                $api->$setterName(true);
            } catch (\BadMethodCallException $e2) {
                // Exception should happen, all good
            }
            if (!$e2) {
                $this->fail('No exception raised when setting invalid field: ' . $fieldName);
            }
            foreach ($api::getOptionalFields() as $fieldName) {
                $methodName = 'wet' . ucfirst($fieldName);
                $e3 = false;
                try {
                    $api->$methodName();
                } catch (\BadMethodCallException $e3) {
                    // Exception should happen, all good
                }
                if (!$e3) {
                    $this->fail('No exception raised when using invalid prefix: ' . $fieldName);
                }
            }
        }
    }

    public function testFieldGettersSuccess()
    {
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $fields = $api::getOptionalFields();
            foreach ($fields as $field) {
                $getterName = 'get' . ucfirst($field);
                $this->assertTrue(is_bool($api->$getterName()));
            }
        }
    }
}