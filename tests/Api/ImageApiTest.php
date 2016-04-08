<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ImageApiTest extends \PHPUnit_Framework_TestCase
{
    use setterUpper;

    protected $validMock;

    /**
     * @var \Swader\Diffbot\Api\Image
     */
    protected $apiWithMock;

    protected function setUp()
    {
        $diffbot = $this->preSetUp();

        $this->apiWithMock = $diffbot->createImageAPI('https://article-mock.com');
    }

    protected function getValidMock()
    {
        if (!$this->validMock) {
            $this->validMock = new MockHandler([
                    new Response(200, [],
                        file_get_contents(__DIR__ . '/../Mocks/Images/one_image_zola.json'))
            ]);
        }

        return $this->validMock;
    }

    public function testCall()
    {
        $this->apiWithMock->call();
    }

    public function testBuildUrlNoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&timeout=30000';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOneCustomField()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&timeout=30000&fields=meta';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlTwoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&timeout=30000&fields=meta,links';
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
        $expectedUrl = 'https://api.diffbot.com/v3/image?token=demo&url=https%3A%2F%2Farticle-mock.com&timeout=30000&fields=meta,links,breadcrumb,querystring,ocr,faces,mentions';
        $this->assertEquals($expectedUrl, $url);
    }

}
