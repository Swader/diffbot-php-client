<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Client;

/**
 * @property $files array
 * @property $responses array
 *
 * @package Swader\Diffbot\Test
 */
class ResponseProvider extends \PHPUnit_Framework_TestCase {
    protected function prepareResponses()
    {
        if (empty($this->responses)) {
            $mockInput = [];
            foreach ($this->files as $file) {
                $mockInput[] = file_get_contents(__DIR__ . '/Mocks/' . $file);
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

}