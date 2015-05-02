<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Entity\Image;

class ImageApiTest extends \PHPUnit_Framework_TestCase
{

    protected $validMock;

    /**
     * @var \Swader\Diffbot\Api\Image
     */
    protected $apiWithMock;

    protected function setUp()
    {
        $diffbot = $this->getValidDiffbotInstance();
        $fakeClient = new Client();
        $fakeClient->getEmitter()->attach($this->getValidMock());

        $diffbot->setHttpClient($fakeClient);
        $diffbot->setEntityFactory();

        $this->apiWithMock = $diffbot->createImageAPI('https://article-mock.com');
    }

    protected function getValidDiffbotInstance()
    {
        return new Diffbot('demo');
    }

    protected function getValidMock()
    {
        if (!$this->validMock) {
            $this->validMock = new Mock(
                [file_get_contents(__DIR__ . '/../Mocks/Images/one_image_zola.json')]
            );
        }

        return $this->validMock;
    }

    public function testCall()
    {
        /** @var Image $image */
        $image = $this->apiWithMock->call();
    }

    public function testBuildUrlNoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOneCustomField()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlTwoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta,links';
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
            ->setOcr(true)
            ->setFaces(true)
            ->setMentions(true)
            ->buildUrl();
        $expectedUrl = 'http://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&fields=meta,links,breadcrumb,querystring,ocr,faces,mentions';
        $this->assertEquals($expectedUrl, $url);
    }

}
