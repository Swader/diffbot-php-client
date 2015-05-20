<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\Article;

class ArticleApiTest extends \PHPUnit_Framework_TestCase
{

    protected $validMock;

    /**
     * @var \Swader\Diffbot\Api\Article
     */
    protected $apiWithMock;

    protected function setUp()
    {
        $diffbot = $this->getValidDiffbotInstance();
        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($this->getValidMock());

        $diffbot->setHttpClient($fakeClient);
        $diffbot->setEntityFactory();

        $this->apiWithMock = $diffbot->createArticleAPI('https://article-mock.com');
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

    public function testCall()
    {
        /** @var Article $article */
        $article = $this->apiWithMock->call();

    }

    public function testBuildUrlNoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOneCustomField()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlTwoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta,links';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlFourCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->setBreadcrumb(true)
            ->setQuerystring(true)
            ->setSentiment(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta,links,breadcrumb,querystring,sentiment';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOtherOptionsOnly()
    {
        $url = $this->apiWithMock
            ->setPaging(false)
            ->setMaxTags(10)
            ->setDiscussion(false)
            ->buildUrl();

        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com&paging=false&maxTags=10&discussion=false';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOtherOptionsAndCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->setPaging(false)
            ->setBreadcrumb(true)
            ->setQuerystring(true)
            ->setMaxTags(10)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/article?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta,links,breadcrumb,querystring&paging=false&maxTags=10';
        $this->assertEquals($expectedUrl, $url);
    }

}
