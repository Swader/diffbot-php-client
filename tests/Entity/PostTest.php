<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Discussion;
use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Test\ResponseProvider;
use Swader\Diffbot\Entity\Post;

class PostTest extends ResponseProvider
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
     */
    public function testType($file)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $post) {
                $this->assertEquals('post', $post->getType());
            }
        }
    }

    public function textProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    620,
                    22,
                    636,
                    249,
                    335,
                    62,
                    182,
                    241,
                    28,
                    325,
                    740,
                    680,
                    1145,
                    264,
                    62,
                    10
                ]
            ],
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider textProvider
     */
    public function testText($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i],
                    strlen($post->getText()));
            }
        }
    }

    public function htmlProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    1160,
                    29,
                    664,
                    256,
                    342,
                    69,
                    257,
                    248,
                    35,
                    416,
                    803,
                    732,
                    1412,
                    280,
                    374,
                    42
                ]
            ],
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider htmlProvider
     */
    public function testHtml($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i],
                    strlen($post->getHtml()));
            }
        }
    }

    public function languageProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en',
                    'en'
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider languageProvider
     */
    public function testLanguage($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getHumanLanguage());
            }
        }
    }

    public function idProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13,
                    14,
                    15
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider idProvider
     */
    public function testId($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getId());
            }
        }
    }

    public function parentIdProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    10,
                    10,
                    null,
                    13,
                    13
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider parentIdProvider
     */
    public function testParentId($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getParentId());
            }
        }
    }

    public function tagsCountProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider tagsCountProvider
     */
    public function testTagsCount($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], count($post->getTags()));
                if ($posts[$i] > 0) {
                    $tag = $post->getTags()[0];
                    $this->assertArrayHasKey('id', $tag);
                    $this->assertArrayHasKey('prevalence', $tag);
                    $this->assertArrayHasKey('uri', $tag);
                    $this->assertArrayHasKey('count', $tag);
                    $this->assertArrayHasKey('label', $tag);
                    $this->assertArrayHasKey('type', $tag);
                }
            }
        }
    }

    public function authorProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                    [
                        'TaylorRen',
                        'http://community.sitepoint.com/users/TaylorRen'
                    ],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    ['TomB', 'http://community.sitepoint.com/users/TomB'],
                    [
                        's_molinari',
                        'http://community.sitepoint.com/users/s_molinari'
                    ],
                    ['TomB', 'http://community.sitepoint.com/users/TomB'],
                    ['Wolf_22', 'http://community.sitepoint.com/users/Wolf_22'],
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                    ['swader', 'http://community.sitepoint.com/users/swader'],
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider authorProvider
     */
    public function testAuthor($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i][0], $post->getAuthor());
                $this->assertEquals($posts[$i][1], $post->getAuthorUrl());
            }
        }
    }

    public function sentimentProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    -0.017030051592244688,
                    0.5146608816329232,
                    -0.0008696028172732392,
                    0.06811616071910631,
                    -0.2820276324149199,
                    -0.043333141726268724,
                    -0.36625386935867393,
                    -0.06169348890179555,
                    0.425572071355682,
                    -0.14253904930078215,
                    -0.08573764162496161,
                    -0.3204418598851459,
                    -0.1621040368406367,
                    -0.41009768310258926,
                    0.14437310263131997,
                    -0.02916516910681341
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider sentimentProvider
     */
    public function testSentiment($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getSentiment());
            }
        }
    }

    public function pageUrlProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                    "http://community.sitepoint.com/t/php7-resource-recap/174325/14",
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider pageUrlProvider
     */
    public function testPageUrl($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getPageUrl());
            }
        }
    }

    public function diffbotUriProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    "post|3|569982108",
                    "post|3|1426760448",
                    "post|3|-1745084692",
                    "post|3|355705735",
                    "post|3|-8418053",
                    "post|3|-1235284844",
                    "post|3|591433314",
                    "post|3|-678683828",
                    "post|3|-1189567303",
                    "post|3|77282811",
                    "post|3|-1327995688",
                    "post|3|1424541997",
                    "post|3|-414471161",
                    "post|3|-270422882",
                    "post|3|257648606",
                    "post|3|1471337096",
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider diffbotUriProvider
     */
    public function testDiffbotUri($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getDiffbotUri());
            }
        }
    }

    public function dateProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [
                    "Wed, 29 Apr 2015 16:00:00 GMT",
                    "Thu, 30 Apr 2015 01:13:00 GMT",
                    "Thu, 30 Apr 2015 05:55:00 GMT",
                    "Thu, 30 Apr 2015 06:57:00 GMT",
                    "Thu, 30 Apr 2015 07:51:00 GMT",
                    "Thu, 30 Apr 2015 09:29:00 GMT",
                    "Thu, 30 Apr 2015 10:26:00 GMT",
                    "Thu, 30 Apr 2015 10:40:00 GMT",
                    "Thu, 30 Apr 2015 11:06:00 GMT",
                    "Thu, 30 Apr 2015 11:29:00 GMT",
                    "Thu, 30 Apr 2015 14:33:00 GMT",
                    "Thu, 30 Apr 2015 15:48:00 GMT",
                    "Thu, 30 Apr 2015 16:17:00 GMT",
                    "Thu, 30 Apr 2015 16:51:00 GMT",
                    "Thu, 30 Apr 2015 17:02:00 GMT",
                    "Fri, 01 May 2015 08:00:00 GMT",
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider dateProvider
     */
    public function testDate($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getDate());
            }
        }
    }

    public function mediaCountProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider mediaCountProvider
     */
    public function testMedia($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], count($post->getImages()));
            }
        }
    }

    public function votesProvider()
    {
        return [
            [
                'Discussions/15-05-01/sp_discourse_php7_recap.json',
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ]
        ];
    }

    /**
     * @param $file
     * @param $posts
     * @dataProvider votesProvider
     */
    public function testVotes($file, $posts)
    {
        /** @var Discussion $entity */
        foreach ($this->ei($file) as $entity) {
            /** @var Post $post */
            foreach ($entity->getPosts() as $i => $post) {
                $this->assertEquals($posts[$i], $post->getVotes());
            }
        }
    }

}
