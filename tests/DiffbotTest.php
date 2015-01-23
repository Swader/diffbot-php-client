<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Exceptions\DiffbotException;

/**
 * @runTestsInSeparateProcesses
 */
class DiffbotTest extends \PHPUnit_Framework_TestCase
{

    private $invalidTokens = [
        '',
        'a',
        'ab',
        'abc',
        1,
        12,
        123,
        true,
        array('token')
    ];

    private $validTokens = [
        'token',
        '123456789',
        'akrwejhtn983z420qrzc8397r4'
    ];

    public function testStaticSetToken()
    {
        foreach ($this->invalidTokens as $value) {
            try {
                Diffbot::setToken($value);
            } catch (\InvalidArgumentException $e) {
                // Good, we got an exception!
                continue;
            }
            $this->fail('Expected exception not raised on value: "' . $value . '".');
        }

        foreach ($this->validTokens as $value) {
            Diffbot::setToken($value);
        }
    }

    public function testInstantiation()
    {
        $exceptionTriggered = false;
        try {
            new Diffbot();
        } catch (DiffbotException $e) {
            // Great, got it!
            $exceptionTriggered = true;
        }
        if (!$exceptionTriggered) {
            $this->fail('Empty token did not produce exception!');
        }

        try {
            Diffbot::setToken('token');
            new Diffbot();
        } catch (DiffbotException $e) {
            $this->fail('Scenario failed!');
        }

        try {
            new Diffbot('token');
        } catch (DiffbotException $e) {
            $this->fail('Scenario failed!');
        }
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