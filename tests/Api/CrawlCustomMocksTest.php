<?php

namespace Swader\Diffbot\Test\Api;

use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\JobCrawl;

class CrawlCustomMocksTest extends \PHPUnit_Framework_TestCase
{
    use setterUpper;

    /** @var Diffbot */
    protected $diffbot;

    public function setUp()
    {
        $diffbot = new Diffbot('demo');
        $diffbot->setEntityFactory();
        $this->diffbot = $diffbot;
    }

    public function testRoundStart()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_roundstart.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->roundStart();

        $this->assertTrue($j->getRoundStartTime() == $j->getCurrentTime());
        $this->assertTrue($j->getPageCrawlInfo()['successesThisRound'] == 0);
    }

    public function testRestart()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_restart.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->restart();

        $this->assertTrue($j->getObjectsFound() == 0);
        $this->assertTrue($j->getUrlsHarvested() == 0);
        $this->assertTrue($j->getPageCrawlInfo()['successes'] == 0);
        $this->assertTrue($j->getPageCrawlInfo()['attempts'] == 0);
        $this->assertTrue($j->getPageCrawlInfo()['successesThisRound'] == 0);
        $this->assertTrue($j->getPageProcessInfo()['successes'] == 0);
        $this->assertTrue($j->getPageProcessInfo()['attempts'] == 0);
        $this->assertTrue($j->getPageProcessInfo()['successesThisRound'] == 0);
    }

    public function testPauseOn()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_paused.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->pause();

        $this->assertEquals(6, $j->getJobStatus()['status']);
        $this->assertEquals('Job paused.', $j->getJobStatus()['message']);
    }

    public function testPauseOff()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_unpaused.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->unpause();

        $this->assertEquals(7, $j->getJobStatus()['status']);
        $this->assertEquals('Job is in progress.', $j->getJobStatus()['message']);
    }

    public function testDelete()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/deletedSuccess.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->assertEquals('Successfully deleted job.', $c->delete());
    }

    public function test500()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/invalid_name.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath, 500));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->setExpectedException('Http\Client\Exception\HttpException');
        $c->call();
    }

    public function testOtherError()
    {
        $filepath = __DIR__ . '/../Mocks/Crawlbot/15-05-20/invalid_response.json';
        $this->diffbot->setHttpClient($this->getCustomMockFakeClient($filepath));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
        $c->call();
    }
}
