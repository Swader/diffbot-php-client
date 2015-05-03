<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Wildcard;
use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Test\ResponseProvider;

class WildCardTest extends ResponseProvider
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

    public function customFieldProvider()
    {
        return [
            [
                'Custom/AuthorFolioNew/15-05-03/bskvorc.json',
                [
                    ['author', 'Bruno Skvorc'],
                    ['bioText', 387, 'test-length']
                ]
            ],
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider customFieldProvider
     */
    public function testCustomFields($file, $posts)
    {
        $posts = (is_array($posts)) ? $posts : [$posts];
        /** @var Wildcard $entity */
        foreach ($this->ei($file) as $i => $entity) {

            $property = $posts[$i][0];
            $method = 'get'.ucfirst($property);
            $value = $posts[$i][1];

            if (!isset($posts[$i][2])) {
                $posts[$i][2] = null;
            }

            switch ($posts[$i][2]) {
                case 'test-length':
                    $this->assertEquals($value, strlen($entity->$property));
                    $this->assertEquals($value, strlen($entity->$method()));
                    break;
                default:
                    $this->assertEquals($value, $entity->$property);
                    $this->assertEquals($value, $entity->$method());
            }
        }
    }

}
