<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Exceptions\DiffbotException;

/**
 * @runTestsInSeparateProcesses
 */
class DiffbotTest extends \PHPUnit_Framework_TestCase
{

    public function invalidTokens()
    {
        return [
            'empty'        => [ '' ],
            'a'            => [ 'a' ],
            'ab'           => [ 'ab' ],
            'abc'          => [ 'abc' ],
            'digit'        => [ 1 ],
            'double-digit' => [ 12 ],
            'triple-digit' => [ 123 ],
            'bool'         => [ true ],
            'array'        => [ ['token'] ],
        ];
    }

    public function validTokens()
    {
        return [
            'token'      => [ 'token' ],
            'short-hash' => [ '123456789' ],
            'full-hash'  => [ 'akrwejhtn983z420qrzc8397r4' ],
        ];
    }

    /**
     * @dataProvider invalidTokens
     */
    public function testSetTokenRaisesExceptionOnInvalidToken($token)
    {
        $this->setExpectedException('InvalidArgumentException');
        Diffbot::setToken($token);
    }

    /**
     * @dataProvider validTokens
     */
    public function testSetTokenSucceedsOnValidToken($token)
    {
        Diffbot::setToken($token);
        $bot = new Diffbot();
        $this->assertInstanceOf('\Swader\Diffbot\Diffbot', $bot);
    }

    public function testInstantiationWithNoGlobalTokenAndNoArgumentRaisesAnException()
    {
        $this->setExpectedException('\Swader\Diffbot\Exceptions\DiffbotException');
        new Diffbot();
    }

    public function testInstantiationWithGlobalTokenAndNoArgumentSucceeds()
    {
        Diffbot::setToken('token');
        $bot = new Diffbot();
        $this->assertInstanceOf('Swader\Diffbot\Diffbot', $bot);
    }

    public function testInstantiationWithNoGlobalTokenButWithArgumentSucceeds()
    {
        $bot = new Diffbot('token');
        $this->assertInstanceOf('Swader\Diffbot\Diffbot', $bot);
    }

    public function testGetToken()
    {
        Diffbot::setToken('testing');
        $d1 = new Diffbot;

        $this->assertEquals('testing', $d1->getToken());

        $sampleToken = 'abcdef';
        $d2 = new Diffbot($sampleToken);

        $this->assertEquals($sampleToken, $d2->getToken());
    }

    public function testApiInstances() {

        $url = 'https://google.com';
        $diffbot = new Diffbot('demo');

        $product = $diffbot->createProductAPI($url);
        $this->assertInstanceOf('\Swader\Diffbot\Api\Product', $product);

        $analyze = $diffbot->createAnalyzeAPI($url);
        $this->assertInstanceOf('\Swader\Diffbot\Api\Analyze', $analyze);

        $image = $diffbot->createImageAPI($url);
        $this->assertInstanceOf('\Swader\Diffbot\Api\Image', $image);

        $article = $diffbot->createArticleAPI($url);
        $this->assertInstanceOf('\Swader\Diffbot\Api\Article', $article);

    }

}