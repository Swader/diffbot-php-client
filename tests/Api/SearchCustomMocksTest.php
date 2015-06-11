<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\JobCrawl;

class SearchCustomMocksTest extends \PHPUnit_Framework_TestCase
{

    /** @var Diffbot */
    protected $diffbot;

    /** @var string */
    protected $mockPrefix = __DIR__ . '/../Mocks/Search/';

    public function setUp()
    {
        $diffbot = new Diffbot('demo');
        $diffbot->setEntityFactory();
        $fakeClient = new Client();
        $diffbot->setHttpClient($fakeClient);
        $this->diffbot = $diffbot;
    }

    public function resultCountProvider()
    {
        return [
            [
                [
                    'file' => '15-05-24/test.json',
                    'q' => 'author:"Miles Johnson" AND type:article'
                ],
                ['results' => 8]
            ]
        ];
    }

    /**
     * @dataProvider resultCountProvider
     * @param $case
     * @param $expectations
     */
    public function testResultCount($case, $expectations)
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents($this->mockPrefix . $case['file'])]
        ));

        $search = $this->diffbot->search($case['q'])->call();

        $this->assertEquals($expectations['results'], $search->count());
    }

    public function searchInfoProvider()
    {
        return [
            [
                [
                    'file' => '15-05-24/test.json',
                    'q' => 'author:"Miles Johnson" AND type:article'
                ],
                [
                    'currentTimeUTC' => 1433609003,
                    'responseTimeMS' => 112,
                    'numResultsOmitted' => 0,
                    'numShardsSkipped' => 0,
                    'totalShards' => 32,
                    'docsInCollection' => 94592,
                    'hits' => 8,
                    'queryInfo' => [
                        "fullQuery" => "type:json AND (author:\"Miles Johnson\" AND type:article)",
                        "queryLanguageAbbr" => "xx",
                        "queryLanguage" => "Unknown",
                        "terms" => [
                            [
                                "termNum" => 0,
                                "termStr" => "Miles Johnson",
                                "termFreq" => 359328,
                                "termHash48" => 224575481707228,
                                "termHash64" => 4150001371756911641,
                                "prefixHash64" => 3732660069076179349
                            ],
                            [
                                "termNum" => 1,
                                "termStr" => "type:json",
                                "termFreq" => 524352,
                                "termHash48" => 272064464231140,
                                "termHash64" => 9877301297136722857,
                                "prefixHash64" => 7586288672657224048
                            ],
                            [
                                "termNum" => 2,
                                "termStr" => "type:article",
                                "termFreq" => 524448,
                                "termHash48" => 210861560163398,
                                "termHash64" => 12449358332005671483,
                                "prefixHash64" => 7586288672657224048
                            ]
                        ]

                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider searchInfoProvider
     * @param $case
     * @param $expectations
     */
    public function testSearchInfo($case, $expectations)
    {
        $this->markTestSkipped('Bugged due to JSONC: https://github.com/Swader/diffbot-php-client/issues/12');
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents($this->mockPrefix . $case['file'])]
        ));

        $searchInfo = $this->diffbot->search($case['q'])->call(true);

        foreach ($expectations as $key => $value) {
            $method = 'get'.ucfirst($key);
            $this->assertEquals($value, $searchInfo->$method());
        }
    }
}
