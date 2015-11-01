<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6HttpAdapter;
use Http\Client\Utils\HttpMethodsClient;
use Http\Discovery\MessageFactory\GuzzleFactory;
use Swader\Diffbot\Diffbot;

class DiffbotTest extends \PHPUnit_Framework_TestCase
{

    public function invalidTokens()
    {
        return [
            'empty' => [''],
            'a' => ['a'],
            'ab' => ['ab'],
            'abc' => ['abc'],
            'digit' => [1],
            'double-digit' => [12],
            'triple-digit' => [123],
            'bool' => [true],
            'array' => [['token']],
        ];
    }

    public function validTokens()
    {
        return [
            'token' => ['token'],
            'short-hash' => ['123456789'],
            'full-hash' => ['akrwejhtn983z420qrzc8397r4'],
        ];
    }

    /**
     * @dataProvider invalidTokens
     * @param $token
     */
    public function testSetTokenRaisesExceptionOnInvalidToken($token)
    {
        $this->setExpectedException('InvalidArgumentException');
        Diffbot::setToken($token);
    }

    /**
     * @dataProvider validTokens
     * @param $token
     */
    public function testSetTokenSucceedsOnValidToken($token)
    {
        Diffbot::setToken($token);
        $bot = new Diffbot();
        $this->assertInstanceOf('\Swader\Diffbot\Diffbot', $bot);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstantiationWithNoGlobalTokenAndNoArgumentRaisesAnException(
    )
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

    public function testSetHttpClient()
    {
        $bot = new Diffbot('token');
        $mock = new MockHandler([
            new Response(200, [],
                file_get_contents(__DIR__ . '/Mocks/Products/dogbrush.json'))
        ]);
        $handler = HandlerStack::create($mock);
        $guzzleClient = new Client(['handler' => $handler]);

        $methodsClient = new HttpMethodsClient(
            new Guzzle6HttpAdapter($guzzleClient),
            new GuzzleFactory());

        try {
            $bot->setHttpClient($methodsClient);
        } catch (\Exception $e) {
            $this->fail("Could not set fake client: " . $e->getMessage());
        }
    }

    public function methodnameProvider()
    {
        return [
            ['product'],
            ['image'],
            ['analyze'],
            ['article'],
            ['discussion']
        ];
    }

    /**
     * @param $methodNamePart
     * @dataProvider methodnameProvider
     */
    public function testAPIcreation($methodNamePart)
    {
        $bot = new Diffbot('token');
        $methodName = 'create' . ucfirst($methodNamePart) . 'API';

        $api = $bot->$methodName('https://foolink.bar');
        $this->assertInstanceOf(
            'Swader\Diffbot\Api\\' . ucfirst($methodNamePart), $api
        );
    }

    public function testCustomApiCreation()
    {
        $bot = new Diffbot('token');
        $api = $bot->createCustomAPI('http://someurl.com', 'apiName');
        $this->assertInstanceOf(
            'Swader\Diffbot\Api\Custom', $api
        );
    }

    public function testCrawlCreation()
    {
        $bot = new Diffbot('token');
        $api = $bot->crawl('test');
        $this->assertInstanceOf(
            'Swader\Diffbot\Api\Crawl', $api
        );
    }

    public function testSearchCreation()
    {
        $bot = new Diffbot('token');
        $api = $bot->search('test');
        $this->assertInstanceOf(
            'Swader\Diffbot\Api\Search', $api
        );
    }

}