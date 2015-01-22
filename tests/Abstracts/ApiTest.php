<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Abstracts\Api;

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
}