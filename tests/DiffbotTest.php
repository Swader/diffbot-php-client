<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
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
        $validMock = new Mock(
            [file_get_contents(__DIR__ . '/Mocks/Products/dogbrush.json')]
        );
        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($validMock);

        try {
            $bot->setHttpClient($fakeClient);
        } catch (\Exception $e) {
            $this->fail("Could not set fake client: " . $e->getMessage());
        }
    }

    public function methodnameProvider()
    {
        return [['product'], ['image'], ['analyze'], ['article'], ['discussion']];
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

}