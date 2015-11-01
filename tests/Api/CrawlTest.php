<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Swader\Diffbot\Diffbot;

class CrawlTest extends \PHPUnit_Framework_TestCase
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
                    file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-18/sitepoint_01_maxCrawled.json'))
            ]);
        }

        return $this->validMock;
    }

    public function testBuildUrlListJobs()
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo';
        $c = $this->diffbot->crawl();
        $this->assertEquals($expected, $c->buildUrl());
    }

    public function testBuildUrlArticleApi()
    {
        $api = $this->diffbot->createArticleAPI('crawl')->setDiscussion(false);

        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Farticle%3F%26discussion%3Dfalse';
        $c = $this->diffbot->crawl('sitepoint_01', $api);
        $c->setSeeds(['http://sitepoint.com']);
        $this->assertEquals($expected, $c->buildUrl());
    }

    public function testBuildUrlDefaultApi()
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);
        $this->assertEquals($expected, $c->buildUrl());
    }

    public function testInvalidSeeds()
    {
        $this->setExpectedException('InvalidArgumentException');

        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com' . 'foo', 'wakakakablah']);
    }

    public function testPatternSetters()
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&pageProcessPattern=class%3DarticleBody&urlCrawlPattern=%2Fcategory%2Fshoes||%21%2Fauthor%2F||%5Ehttp%3A%2F%2Fwww.diffbot.com||type%3Dproduct%24&urlProcessPattern=%2Fproduct%2Fdetail||%21%3Fcurrency%3Deuro&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setPageProcessPatterns(['class=articleBody']);
        $c->setUrlCrawlPatterns([
            '/category/shoes',
            '!/author/',
            '^http://www.diffbot.com',
            'type=product$'
        ]);
        $c->setUrlProcessPatterns(['/product/detail', '!?currency=euro']);

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function testRegexSetters()
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&urlCrawlRegEx=/^[a-z0-9_-]{3,16}$/&urlProcessRegEx=/^#?([a-f0-9]{6}|[a-f0-9]{3})$/&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setUrlCrawlRegEx('/^[a-z0-9_-]{3,16}$/');
        $c->setUrlProcessRegEx('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/');

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function maxHopsProvider()
    {
        return [
            [-100, "-1"],
            [-1, "-1"],
            [0, "0"],
            [1, "1"],
            [5, "5"],
            [100, "100"]
        ];
    }

    /**
     * @dataProvider maxHopsProvider
     * @param $input
     * @param $urlFragment
     */
    public function testMaxHops($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&maxHops=' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setMaxHops($input);

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function maxProvider()
    {
        return [
            [1, "1"],
            [-1, "1"],
            [0, "1"],
            [5, "5"],
            [500, "500"],
            [1000000, "1000000"]
        ];
    }

    /**
     * @dataProvider maxProvider
     * @param $input
     * @param $urlFragment
     */
    public function testMax($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&maxToCrawl=' . $urlFragment . '&maxToProcess=' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setMaxToCrawl($input);
        $c->setMaxToProcess($input);

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function notifyProviderOk()
    {
        return [
            ['bruno@skvorc.me', 'notifyEmail=bruno@skvorc.me'],
            [
                'http://bruno.skvorc.me/somewebhook?diffbotIsDone=true',
                'notifyWebhook=http%3A%2F%2Fbruno.skvorc.me%2Fsomewebhook%3FdiffbotIsDone%3Dtrue'
            ],
            [
                [
                    'bruno@skvorc.me',
                    'http://bruno.skvorc.me/somewebhook?diffbotIsDone=true'
                ],
                'notifyEmail=bruno@skvorc.me&notifyWebhook=http%3A%2F%2Fbruno.skvorc.me%2Fsomewebhook%3FdiffbotIsDone%3Dtrue'
            ]
        ];
    }

    /**
     * @dataProvider notifyProviderOk
     * @param $input
     * @param $urlFragment
     */
    public function testNotify($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        foreach ((array)$input as $i) {
            $c->notify($i);
        }

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function notifyProviderNotOk()
    {
        return [
            [5],
            ['foo'],
            ['htp:/someurl']
        ];
    }

    /**
     * @dataProvider notifyProviderNotOk
     * @param $input
     */
    public function testNotifyFail($input)
    {
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $this->setExpectedException('InvalidArgumentException');
        $c->notify($input);
    }

    public function crawlDelayProviderOk()
    {
        return [
            [0.25, '0.25'],
            [1, '1'],
            [5, '5'],
            [0, '0'],
            [100, '100'],
            [-5, '0.25'],
            [-0.25, '0.25']
        ];
    }

    /**
     * @dataProvider crawlDelayProviderOk
     * @param $input
     * @param $urlFragment
     */
    public function testCrawlOk($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&crawlDelay=' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setCrawlDelay($input);
        $this->assertEquals($expected, $c->buildUrl());
    }

    public function crawlDelayProviderNotOk()
    {
        return [
            ['foo'],
            ['blah'],
            ['0482kjvjs'],
            ['cvojhshjvs4920'],
            [true],
            [false],
            [null]
        ];
    }

    /**
     * @dataProvider crawlDelayProviderNotOk
     * @param $input
     */
    public function testCrawlNotOk($input)
    {
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $this->setExpectedException('InvalidArgumentException');
        $c->setCrawlDelay($input);
    }

    public function repeatProviderOk()
    {
        return [
            [1, '1'],
            [5, '5'],
            [0.25, '0.25']
        ];
    }

    /**
     * @dataProvider repeatProviderOk
     * @param $input
     * @param $urlFragment
     */
    public function testRepeatOk($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&repeat=' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setRepeat($input);
        $this->assertEquals($expected, $c->buildUrl());

    }

    public function repeatProviderNotOk()
    {
        return [
            [0],
            ['foo'],
            [false],
            [null]
        ];
    }

    /**
     * @dataProvider repeatProviderNotOk
     * @param $input
     */
    public function testRepeatNotOk($input)
    {
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $this->setExpectedException('InvalidArgumentException');
        $c->setRepeat($input);
    }

    public function testOnlyProcessIfNew()
    {
        $expected1 = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&onlyProcessIfNew=1&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $expected2 = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&onlyProcessIfNew=0&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';

        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setOnlyProcessIfNew(1);
        $this->assertEquals($expected1, $c->buildUrl());
        $c->setOnlyProcessIfNew(0);
        $this->assertEquals($expected2, $c->buildUrl());
    }

    public function maxRoundsProvider()
    {
        return [
            [-100, "-1"],
            [-1, "-1"],
            [0, "0"],
            [1, "1"],
            [5, "5"],
            [100, "100"]
        ];
    }

    /**
     * @dataProvider maxRoundsProvider
     * @param $input
     * @param $urlFragment
     */
    public function testMaxRounds($input, $urlFragment)
    {
        $expected = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&maxRounds=' . $urlFragment . '&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);

        $c->setMaxRounds($input);

        $this->assertEquals($expected, $c->buildUrl());
    }

    public function testObeyRobots()
    {
        $expected1 = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&obeyRobots=1&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $expected2 = 'https://api.diffbot.com/v3/crawl?token=demo&name=sitepoint_01&seeds=http%3A%2F%2Fsitepoint.com&obeyRobots=0&apiUrl=https%3A%2F%2Fapi.diffbot.com%2Fv3%2Fanalyze%3F%26mode%3Dauto';
        $c = $this->diffbot->crawl('sitepoint_01');
        $c->setSeeds(['http://sitepoint.com']);


        $c->setObeyRobots();
        $this->assertEquals($expected1, $c->buildUrl());

        $c->setObeyRobots(0);
        $this->assertEquals($expected2, $c->buildUrl());
        $c->setObeyRobots(false);
        $this->assertEquals($expected2, $c->buildUrl());
    }
}
