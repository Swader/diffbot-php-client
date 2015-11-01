<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Factory\Entity;

class EntityIteratorTest extends ResponseProvider
{

    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Images/one_image_zola.json',
        'Images/multi_images_smittenkitchen.json'
    ];

    protected $folder = '/../Mocks/';

    public function testBadMethodCall()
    {
        $ef = new Entity();
        $ei = $ef->createAppropriateIterator($this->prepareResponses()['Images/one_image_zola.json']);

        $this->setExpectedException('BadMethodCallException');
        $ei->invalidMethodCall();
    }

    public function testMagic()
    {
        $ef = new Entity();
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

        $ef = new Entity();

        foreach ($fileExpectations as $fileName => $expectation) {
            $ei = $ef->createAppropriateIterator($this->prepareResponses()[$fileName]);
            $this->assertEquals($expectation, count($ei));
        }
    }

    public function testGetResponse()
    {
        $ef = new Entity();

        foreach ($this->files as $fileName) {
            $ei = $ef->createAppropriateIterator($this->prepareResponses()[$fileName]);
            $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $ei->getResponse());
        }
    }

    public function testIteration()
    {
        $ef = new Entity();
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
