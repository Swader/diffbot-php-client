<?php

namespace Swader\Diffbot\Test;

use Swader\Diffbot\Abstracts\Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function buildMock()
    {
        return $this->getMockForAbstractClass('Swader\Diffbot\Abstracts\Api');
    }

    public function testSetTimeout()
    {
        /** @var Api $mock */
        $mock = $this->buildMock();

        $validTimeouts = [
            0,
            1000,
            2000,
            3000,
            3000000,
            40000000,
            null
        ];

        $invalidTimeouts = [
            -298979879827,
            -4983,
            'abcef',
            '',
            false
        ];

        try {
            $mock->setTimeout();
        } catch (\InvalidArgumentException $e) {
            $this->fail('Failed with supposedly valid (empty) timeout.');
        }

        foreach ($validTimeouts as $timeout) {
            try {
                $mock->setTimeout($timeout);
            } catch (\InvalidArgumentException $e) {
                $this->fail('Failed with supposedly valid timeout: ' . $timeout);
            }
        }

        foreach ($invalidTimeouts as $timeout) {
            try {
                $mock->setTimeout($timeout);
            } catch (\InvalidArgumentException $e) {
                // Got expected exception
                continue;
            }
            $this->fail('Failed, assumed invalid parameter was valid.');
        }
    }
}