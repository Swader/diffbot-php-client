<?php

namespace Swader\Diffbot\Test\Factory;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Swader\Diffbot\Diffbot;
use Swader\Diffbot\Factory\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Entity */
    protected $ef;

    /** @var Response */
    protected $responseOk;

    public function setUp()
    {
        $this->responseOk = new Response(200);
        $this->ef = new Entity();
    }

    public function testInvalidResponseBodyFail()
    {
        $this->responseOk->setBody(Stream::factory('Pure text content'));
        $this->setExpectedException('GuzzleHttp\Exception\ParseException');
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testMissingObjectsFail()
    {
        $this->responseOk->setBody(Stream::factory(json_encode(['foo' => 'bar'])));
        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testMissingRequestFail()
    {
        $this->responseOk->setBody(Stream::factory(json_encode([
            'objects' => 'foo',
            'req' => 'bar'
        ])));
        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testMissingApiFail()
    {
        $this->responseOk->setBody(Stream::factory(json_encode([
            'objects' => 'foo',
            'request' => 'bar'
        ])));
        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException');
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testProductEntityPass()
    {
        $this->responseOk->setBody(Stream::factory(json_encode([
            'objects' => [['type' => 'product']],
            'request' => ['api' => 'product', 'foo' => 2]
        ])));
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testWildCardEntityPass()
    {
        $this->responseOk->setBody(Stream::factory(json_encode([
            'objects' => [['type' => 'mysterious_api']],
            'request' => ['api' => 'mysterious_api', 'foo' => 2]
        ])));
        $this->ef->createAppropriateIterator($this->responseOk);
    }

    public function testErrorResponse()
    {
        $diffbot = new Diffbot('invalidToken12345');
        $api = $diffbot->createArticleAPI('http://google.com');

        $arr = ['errorCode' => 401, 'error' => 'Not authorized API token.'];
        $this->setExpectedException('Swader\Diffbot\Exceptions\DiffbotException',
            'Diffbot returned error ' . $arr['errorCode'] . ': ' . $arr['error']);
        $api->call();
    }
}
