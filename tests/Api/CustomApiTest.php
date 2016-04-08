<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Swader\Diffbot\Diffbot;

class CustomApiTest extends \PHPUnit_Framework_TestCase
{
    use setterUpper;

    protected $validMock;

    /** @var Diffbot */
    protected $diffbot;

    protected function setUp()
    {
        $this->diffbot = $this->preSetUp();
    }

    protected function getValidMock()
    {
        if (!$this->validMock) {
            $this->validMock = new MockHandler([
                new Response(200, [],
                    file_get_contents(__DIR__ . '/../Mocks/Articles/hi_quicktip_basic.json'))
            ]);
        }

        return $this->validMock;
    }

    public function apiNameProvider()
    {
        return [
            [
                'custom',
                'https://api.diffbot.com/v3/custom?token=demo&url=http%3A%2F%2Fsample-url.com&timeout=30000'
            ],
            [
                'authorFolioNew',
                'https://api.diffbot.com/v3/authorFolioNew?token=demo&url=http%3A%2F%2Fsample-url.com&timeout=30000'
            ],
            [
                'authorFolioNew/something',
                'https://api.diffbot.com/v3/authorFolioNew/something?token=demo&url=http%3A%2F%2Fsample-url.com&timeout=30000'
            ],
            [
                'my-api',
                'https://api.diffbot.com/v3/my-api?token=demo&url=http%3A%2F%2Fsample-url.com&timeout=30000'
            ],
            [
                'my-api?param=value',
                'https://api.diffbot.com/v3/my-api?param=value?token=demo&url=http%3A%2F%2Fsample-url.com&timeout=30000'
            ]
        ];
    }

    /**
     * @param $name
     * @param $url
     * @dataProvider apiNameProvider
     */
    public function testCreationAndUrl($name, $url)
    {
        $api = $this->diffbot->createCustomAPI('http://sample-url.com', $name);

        $this->assertEquals($url, $api->buildUrl());
    }

//    public function apiBadNameProvider()
//    {
//        return [
//            ['custom.api'],
//            ['someApi~!woot'],
//            ['my-api\\']
//        ];
//    }
//
//    /**
//     * @param $name
//     * @dataProvider apiBadNameProvider
//     */
//    public function testInvalidNames($name)
//    {
//        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
//        $this->diffbot->createCustomAPI('https://sample-url.com', $name);
//    }

}
