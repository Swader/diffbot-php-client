<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;

class CustomApiTest extends \PHPUnit_Framework_TestCase
{

    protected $validMock;

    /** @var Diffbot */
    protected $diffbot;

    protected function setUp()
    {
        $diffbot = $this->getValidDiffbotInstance();
        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($this->getValidMock());

        $diffbot->setHttpClient($fakeClient);
        $diffbot->setEntityFactory();

        $this->diffbot = $diffbot;
    }

    protected function getValidDiffbotInstance()
    {
        return new Diffbot('demo');
    }

    protected function getValidMock()
    {
        if (!$this->validMock) {
            $this->validMock = new Mock(
                [file_get_contents(__DIR__ . '/../Mocks/Articles/hi_quicktip_basic.json')]
            );
        }

        return $this->validMock;
    }

    public function apiNameProvider()
    {
        return [
            [
                'custom',
                'http://api.diffbot.com/v3/custom?token=demo&url=http%3A%2F%2Fsample-url.com'
            ],
            [
                'authorFolioNew',
                'http://api.diffbot.com/v3/authorFolioNew?token=demo&url=http%3A%2F%2Fsample-url.com'
            ],
            [
                'authorFolioNew/something',
                'http://api.diffbot.com/v3/authorFolioNew/something?token=demo&url=http%3A%2F%2Fsample-url.com'
            ],
            [
                'my-api',
                'http://api.diffbot.com/v3/my-api?token=demo&url=http%3A%2F%2Fsample-url.com'
            ],
            [
                'my-api?param=value',
                'http://api.diffbot.com/v3/my-api?param=value?token=demo&url=http%3A%2F%2Fsample-url.com'
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
//        $this->diffbot->createCustomAPI('http://sample-url.com', $name);
//    }

}
