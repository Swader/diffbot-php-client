<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Abstracts\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Entity */
    protected $mock;

    public function testGetData() {
        /** @var Entity $mock */
        $mock = $this->getMockForAbstractClass('Swader\Diffbot\Abstracts\Entity', [[]]);
        $data = $mock->getData();

        $this->assertEquals([], $data);
    }

}
