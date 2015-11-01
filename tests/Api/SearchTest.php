<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Api\Search;

class SearchTest extends \PHPUnit_Framework_TestCase
{
    use setterUpper;

    protected $validMock;

    /** @var  Diffbot */
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
                    file_get_contents(__DIR__ . '/../Mocks/Search/15-05-24/test.json'))
            ]);
        }

        return $this->validMock;
    }

    public function urlFragmentsProvider()
    {
        return [
            [[], "https://api.diffbot.com/v3/search?token=demo&query=test"],
            [
                ['col' => 'foo'],
                "https://api.diffbot.com/v3/search?token=demo&query=test&col=foo"
            ],
            [
                ['col' => null],
                "https://api.diffbot.com/v3/search?token=demo&query=test"
            ],
            [
                ['num' => 10],
                "https://api.diffbot.com/v3/search?token=demo&query=test&num=10"
            ],
            [
                ['start' => 20],
                "https://api.diffbot.com/v3/search?token=demo&query=test&start=20"
            ],
            [
                ['col' => 'foo', 'num' => 10],
                "https://api.diffbot.com/v3/search?token=demo&query=test&col=foo&num=10"
            ],
            [
                ['col' => 'foo', 'num' => 10, 'start' => 20],
                "https://api.diffbot.com/v3/search?token=demo&query=test&col=foo&num=10&start=20"
            ],
            [
                ['col' => 'foo', 'start' => 20],
                "https://api.diffbot.com/v3/search?token=demo&query=test&col=foo&start=20"
            ],
            [
                ['num' => 10, 'start' => 20],
                "https://api.diffbot.com/v3/search?token=demo&query=test&num=10&start=20"
            ],
        ];
    }

    /**
     * @dataProvider urlFragmentsProvider
     * @param $fragments
     * @param $expected
     */
    public function testBuildUrl($fragments, $expected)
    {
        /** @var Search $search */
        $search = $this->diffbot->search('test');

        if (array_key_exists('col', $fragments)) {
            $search->setCol($fragments['col']);
        }

        if (array_key_exists('num', $fragments)) {
            $search->setNum($fragments['num']);
        }

        if (array_key_exists('start', $fragments)) {
            $search->setStart($fragments['start']);
        }

        $this->assertEquals($expected, $search->buildUrl());
    }

    public function urlFragmentInvalidProvider()
    {
        return [
            [['num' => 'foo']],
            [['start' => 'foo']]
        ];
    }

    /**
     * @dataProvider urlFragmentInvalidProvider
     * @param $fragments
     */
    public function testBuildUrlNok($fragments)
    {
        /** @var Search $search */
        $search = $this->diffbot->search('test');

        $this->setExpectedException('InvalidArgumentException');
        if (isset($fragments['num'])) {
            $search->setNum($fragments['num']);
        }

        if (isset($fragments['start'])) {
            $search->setStart($fragments['start']);
        }

    }
}
