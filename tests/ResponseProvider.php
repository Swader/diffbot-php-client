<?php

namespace Swader\Diffbot\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Swader\Diffbot\Factory\Entity;

/**
 * @property $files array
 * @property $responses array
 *
 * @package Swader\Diffbot\Test
 */
class ResponseProvider extends \PHPUnit_Framework_TestCase
{
    protected $folder = '/Mocks/';

    protected static $staticResponses = [];
    protected static $staticFiles = [];

    protected function prepareResponses()
    {
        if (empty($this->responses)) {
            foreach ($this->files as $file) {

                $path = __DIR__ . '/Mocks/' . $file;
                if (!is_readable($path)) {
                    throw new \InvalidArgumentException("Test will error because mock file '$path' ($file) not readable!");
                }
                $contents = file_get_contents($path);

                $this->responses[$file] = new Response(200, [], $contents);
            }
            unset($file);
        }

        return $this->responses;
    }

    protected static function prepareResponsesStatic()
    {
        if (empty(self::$staticResponses)) {
            foreach (static::$staticFiles as $file) {
                $path = __DIR__ . '/Mocks/' . $file;
                if (!is_readable($path)) {
                    throw new \InvalidArgumentException("Test will error because mock file '$path' ($file) not readable!");
                }
                $contents = file_get_contents($path);
                self::$staticResponses[$file] = new Response(200, [], $contents);
            }
        }

        return self::$staticResponses;
    }

    public static function setUpBeforeClass()
    {
        self::prepareResponsesStatic();
    }

    public static function tearDownAfterClass()
    {
        self::$staticResponses = [];
    }

    protected function ei($file)
    {
        $ef = new Entity();
        return $ef->createAppropriateIterator(self::prepareResponsesStatic()[$file]);
    }

    public function returnFiles()
    {
        $files = [];
        foreach (static::$staticFiles as $file) {
            $files[] = [$file];
        }

        return $files;
    }



}