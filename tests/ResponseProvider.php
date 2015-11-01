<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * @property $files array
 * @property $responses array
 *
 * @package Swader\Diffbot\Test
 */
class ResponseProvider extends \PHPUnit_Framework_TestCase
{
    protected $folder = '/Mocks/';

    protected function prepareResponses()
    {
        if (empty($this->responses)) {
            $mockInput = [];
            foreach ($this->files as $file) {
                //$mockInput[] = file_get_contents(__DIR__ . '/Mocks/' . $file);
                $this->responses[$file] = new Response(200, [],
                    file_get_contents(__DIR__ . '/Mocks/' . $file));
            }
            unset($file);
//
//            $mock = new Mock($mockInput);
//            $client = new Client();
//            $client->getEmitter()->attach($mock);
//
//            foreach ($this->files as $file) {
//                $this->responses[$file] = $client->get('sampleurl.com');
//            }
//            unset($file);
        }

        return $this->responses;
    }

}