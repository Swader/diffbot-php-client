<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Factory\Entity;

class EntityTest extends ResponseProvider
{

    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Custom/AuthorFolioNew/15-05-03/bskvorc.json',
    ];

    protected function ei($file)
    {
        $ef = new Entity();

        return $ef->createAppropriateIterator($this->prepareResponses()[$file]);
    }

    public function returnFiles()
    {
        $files = [];
        foreach ($this->files as $file) {
            $files[] = [$file];
        }

        return $files;
    }

    public function testInvalidMethodCall()
    {

        foreach ($this->ei('Custom/AuthorFolioNew/15-05-03/bskvorc.json') as $i => $entity) {
            $this->setExpectedException('BadMethodCallException');
            $entity->callInvalidMethod();
        }
    }
}