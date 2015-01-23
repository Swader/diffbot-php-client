<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Exceptions\DiffbotException;

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

    public function testSetTimeout()
    {
        /** @var Api $mock */
        $mock = $this->buildMock();

        $validTimeouts = [
            0,
            1000,
            2000,
            3000,
            3000000,
            40000000,
            null
        ];

        $invalidTimeouts = [
            -298979879827,
            -4983,
            'abcef',
            '',
            false
        ];

        try {
            $mock->setTimeout();
        } catch (\InvalidArgumentException $e) {
            $this->fail('Failed with supposedly valid (empty) timeout.');
        }

        foreach ($validTimeouts as $timeout) {
            try {
                $mock->setTimeout($timeout);
            } catch (\InvalidArgumentException $e) {
                $this->fail('Failed with supposedly valid timeout: ' . $timeout);
            }
        }

        foreach ($invalidTimeouts as $timeout) {
            try {
                $mock->setTimeout($timeout);
            } catch (\InvalidArgumentException $e) {
                // Got expected exception
                continue;
            }
            $this->fail('Failed, assumed invalid parameter was valid.');
        }
    }

    public function testConstructor()
    {
        $validUrls = [
            'http://google.com',
            'http://gigaom.com/cloud/silicon-valley-royalty-pony-up-2m-to-scale-diffbots-visual-learning-robot/',
            <<<'TAG'
http://techcrunch.com/2012/05/31/diffbot-raises-2-million-seed-round-for-web-content-extraction-technology/
TAG
            ,
            'http://www.theverge.com/2012/5/31/3054444/diffbot-raises-2-million-apps-open-web',
            'http://venturebeat.com/2012/08/16/diffbot-api-links',
            'http://www.wired.co.uk/news/archive/2012-06/01/diffbot',
            'http://www.amazon.com/Oh-The-Places-Youll-Go/dp/0679805273/',
            'http://us.levi.com/product/index.jsp?productId=2076855',
            <<<'TAG'
http://www.petsmart.com/dog/grooming-supplies/grreat-choice-soft-slicker-dog-brush-zid36-12094/cat-36-catid-100016
TAG
            ,
            'http://instagram.com/p/t879OvgvqS/',
            'http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/',
            'https://twitter.com/NASA/status/525397368116895744',
            'www.example.com',
            'example.com'
        ];

        $invalidUrls = [
            false,
            null,
            12345,
            'abc',
            '35tugz---sdf----?//*****/*//*'
        ];

        foreach ($validUrls as $i => $url) {
            try {
                $this->getMockForAbstractClass($this->className, [$url]);
            } catch (\InvalidArgumentException $e) {
                $this->fail('Failed with supposedly valid URL: ' . $url . ' at index ' . $i);
            }
        }

        foreach ($invalidUrls as $i => $url) {
            try {
                $this->getMockForAbstractClass($this->className, [$url]);
            } catch (\InvalidArgumentException $e) {
                continue;
            }
            $this->fail('Did not fail with invalid URL at index ' . $i);
        }
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

    public function testFieldSettersSuccess()
    {
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $fields = $api::getOptionalFields();
            foreach ($fields as $field) {
                $setterName = 'set' . ucfirst($field);
                $values = [true, false, null];
                $i = 1000;
                while ($i--) {
                    $v = $values[rand(0, count($values) - 1)];
                    try {
                        $api->$setterName($v);
                    } catch (\InvalidArgumentException $e) {
                        $this->fail($e->getMessage());
                    }
                }
            }
        }
    }

    public function testFieldSettersFail()
    {
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $fields = $api::getOptionalFields();
            foreach ($fields as $field) {
                $setterName = 'set' . ucfirst($field);
                $values = ['true', 'false', 5, 0, 1, '', 5 + 5, 'hello', [true], [false], [true, false]];
                $i = 1000;
                while ($i--) {
                    $v = $values[rand(0, count($values) - 1)];
                    try {
                        $api->$setterName($v);
                    } catch (\InvalidArgumentException $e) {
                        // All good, we got the exception, next iteration please
                        continue;
                    }
                    $message = 'ProductAPI did not error when given wrong input into ' . $setterName . '. ';
                    $message .= 'The input was: ' . print_r($v, true);
                    $this->fail($message);
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

    public function testFieldFail()
    {
        $invalidFieldNames = ['gibberish', 'nonsense', 'folly'];
        /**
         * @var  $name string
         * @var  $api Api
         */
        foreach ($this->returnApis() as $name => $api) {
            $settableValues = [true, false, null];
            foreach ($invalidFieldNames as $field) {
                $getterName = 'get' . ucfirst($field);
                $setterName = 'set' . ucfirst($field);

                $e1 = false;
                try {
                    $api->$getterName();
                } catch (\BadMethodCallException $e1) {
                    // Exception should happen, all good
                }
                if (!$e1) {
                    $this->fail('No exception raised when getting invalid field: ' . $field);
                }

                $e2 = false;
                foreach ($settableValues as $value) {
                    try {
                        $api->$setterName($value);
                    } catch (\BadMethodCallException $e2) {
                        // Exception should happen, all good
                    }
                    if (!$e2) {
                        $this->fail('No exception raised when setting invalid field: ' . $field);
                    }
                }
            }
            foreach ($api::getOptionalFields() as $field) {
                $methodName = 'wet' . ucfirst($field);
                $e3 = false;
                try {
                    $api->$methodName();
                } catch (\BadMethodCallException $e3) {
                    // Exception should happen, all good
                }
                if (!$e3) {
                    $this->fail('No exception raised when using invalid prefix: ' . $field);
                }
            }
        }

    }
}