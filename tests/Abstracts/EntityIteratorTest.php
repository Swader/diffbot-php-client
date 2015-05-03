<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Swader\Diffbot\Abstracts\Entity;

class EntityIteratorTest extends \PHPUnit_Framework_TestCase
{

    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Images/one_image_zola.json',
        'Images/multi_images_smittenkitchen.json'
    ];

    protected function prepareResponses()
    {
        if (empty($this->responses)) {
            $mockInput = [];
            foreach ($this->files as $file) {
                $mockInput[] = file_get_contents(__DIR__ . '/../Mocks/' . $file);
            }
            unset($file);
            $mock = new Mock($mockInput);
            $client = new Client();
            $client->getEmitter()->attach($mock);
            foreach ($this->files as $file) {
                $this->responses[$file] = $client->get('sampleurl.com');
            }
            unset($file);
        }
        return $this->responses;
    }

    public function testBadMethodCall()
    {
        $ef = new \Swader\Diffbot\Factory\Entity();
        $ei = $ef->createAppropriateIterator($this->prepareResponses()['Images/one_image_zola.json']);

        $this->setExpectedException('BadMethodCallException');
        $ei->invalidMethodCall();
    }

    public function testMagic()
    {
        $ef = new \Swader\Diffbot\Factory\Entity();
        $ei = $ef->createAppropriateIterator($this->prepareResponses()['Images/one_image_zola.json']);

        $this->assertEquals('image', $ei->type);
        $this->assertEquals('image', $ei->getType());
    }

    public function testCount()
    {
        $fileExpectations = [
            'Images/one_image_zola.json' => 1,
            'Images/multi_images_smittenkitchen.json' => 9
        ];

        $ef = new \Swader\Diffbot\Factory\Entity();

        foreach ($fileExpectations as $fileName => $expectation) {
            $ei = $ef->createAppropriateIterator($this->prepareResponses()[$fileName]);
            $this->assertEquals($expectation, count($ei));
        }
    }

    public function testGetResponse()
    {
        $ef = new \Swader\Diffbot\Factory\Entity();

        foreach ($this->files as $fileName) {
            $ei = $ef->createAppropriateIterator($this->prepareResponses()[$fileName]);
            $this->assertInstanceOf('GuzzleHttp\Message\Response', $ei->getResponse());
        }
    }

    public function testIteration()
    {
        $ef = new \Swader\Diffbot\Factory\Entity();
        foreach ($this->files as $fileName) {

            $ei = $ef->createAppropriateIterator($this->prepareResponses()[$fileName]);

            foreach ($ei as $entity) {
                $this->assertInstanceOf('Swader\Diffbot\Abstracts\Entity', $entity, $fileName);
            }

            $this->assertEquals(count($ei), $ei->key());

            foreach ($ei as $entity) {
                $this->assertInstanceOf('Swader\Diffbot\Abstracts\Entity', $entity, $fileName);
            }

        }
    }
}
