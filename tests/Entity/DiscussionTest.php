<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Discussion;
use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Test\ResponseProvider;

class DiscussionTest extends ResponseProvider
{
    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Discussions/15-05-01/sp_discourse_php7_recap.json',
        //http%3A%2F%2Fcommunity.sitepoint.com%2Ft%2Fphp7-resource-recap%2F174325%2F14
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

    /**
     * @dataProvider returnFiles
     * @param $file
     */
    public function testType($file)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            $this->assertEquals('discussion', $entity->getType());
        }
    }

    public function pagesProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', 1, [], 'http://community.sitepoint.com/t/php7-resource-recap/174325/2'],
        ];
    }

    /**
     * @param $file
     * @param $discussionsNumPages
     * @param $discussionsNextPages
     * @param $discussionNextPage
     * @dataProvider pagesProvider
     */
    public function testPages($file, $discussionsNumPages, $discussionsNextPages, $discussionNextPage)
    {
        $discussionsNumPages = (is_array($discussionsNumPages) && !empty($discussionsNumPages))
            ? $discussionsNumPages : [$discussionsNumPages];
        $discussionsNextPages = (is_array($discussionsNextPages) && !empty($discussionsNextPages))
            ? $discussionsNextPages : [$discussionsNextPages];
        $discussionNextPage = (is_array($discussionNextPage) && !empty($discussionNextPage))
            ? $discussionNextPage : [$discussionNextPage];

        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussionsNextPages[$i], $entity->getNextPages());
            $this->assertEquals($discussionsNumPages[$i], $entity->getNumPages());
            $this->assertEquals($discussionNextPage[$i], $entity->getNextPage());
        }
    }

    public function tagsCountProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', 0],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider tagsCountProvider
     */
    public function testTagsCount($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], count($entity->getTags()));
            if ($discussions[$i] > 0) {
                $tag = $entity->getTags()[0];
                $this->assertArrayHasKey('id', $tag);
                $this->assertArrayHasKey('prevalence', $tag);
                $this->assertArrayHasKey('uri', $tag);
                $this->assertArrayHasKey('count', $tag);
                $this->assertArrayHasKey('label', $tag);
                $this->assertArrayHasKey('type', $tag);
            }
        }
    }

    public function numPostsProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', 16],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider numPostsProvider
     */
    public function testNumPosts($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], $entity->getNumPosts());
            $this->assertEquals($discussions[$i], count($entity->getPosts()));
            if ($entity->getNumPosts()) {
                $this->assertInstanceOf('Swader\Diffbot\Entity\Post', $entity->getPosts()[0]);
            }
        }
    }

    public function numParticipantsProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', 5],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider numParticipantsProvider
     */
    public function testParticipants($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], $entity->getParticipants());
        }
    }

    public function providerProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', null],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider providerProvider
     */
    public function testProvider($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], $entity->getProvider());
        }
    }

    public function rssProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', "http://community.sitepoint.com/t/php7-resource-recap/174325.rss"],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider rssProvider
     */
    public function testRss($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], $entity->getRssUrl());
        }
    }

    public function confidenceProvider()
    {
        return [
            ['Discussions/15-05-01/sp_discourse_php7_recap.json', 0.17636591966815487],
        ];
    }

    /**
     * @param $file
     * @param $discussions
     * @dataProvider confidenceProvider
     */
    public function testConfidence($file, $discussions)
    {
        $discussions = (is_array($discussions)) ? $discussions : [$discussions];
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($discussions[$i], $entity->getConfidence());
        }
    }
}
