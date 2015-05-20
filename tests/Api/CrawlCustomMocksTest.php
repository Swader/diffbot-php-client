<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\JobCrawl;

class CrawlCustomMocksTest extends \PHPUnit_Framework_TestCase
{

    /** @var Diffbot */
    protected $diffbot;

    public function setUp()
    {
        $diffbot = new Diffbot('demo');
        $diffbot->setEntityFactory();
        $fakeClient = new Client();
        $diffbot->setHttpClient($fakeClient);
        $this->diffbot = $diffbot;
    }

    public function testRoundStart()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_roundstart.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->roundStart();

        $this->assertTrue($j->getRoundStartTime() == $j->getCurrentTime());
        $this->assertTrue($j->getPageCrawlInfo()['successesThisRound'] == 0);
    }

    public function testRestart()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_restart.json')]
        ));

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
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_paused.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->pause();

        $this->assertEquals(6, $j->getJobStatus()['status']);
        $this->assertEquals('Job paused.', $j->getJobStatus()['message']);
    }

    public function testPauseOff()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/sitepoint_01_unpaused.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        /** @var JobCrawl $j */
        $j = $c->unpause();

        $this->assertEquals(7, $j->getJobStatus()['status']);
        $this->assertEquals('Job is in progress.', $j->getJobStatus()['message']);
    }

    public function testDelete()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/deletedSuccess.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->assertEquals('Successfully deleted job.', $c->delete());
    }

    public function test500()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/invalid_name.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->setExpectedException('GuzzleHttp\Exception\ServerException');
        $c->call();
    }

    public function testOtherError()
    {
        $this->diffbot->getHttpClient()->getEmitter()->attach(new Mock(
            [file_get_contents(__DIR__ . '/../Mocks/Crawlbot/15-05-20/invalid_response.json')]
        ));

        $c = $this->diffbot->crawl('sitepoint_01');

        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
        $c->call();
    }
}
