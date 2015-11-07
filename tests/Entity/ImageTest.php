<?php

namespace Swader\Diffbot\Test\Entity;

use Swader\Diffbot\Entity\Image;
use Swader\Diffbot\Test\ResponseProvider;

class ImageTest extends ResponseProvider
{
    protected static $staticFiles = [
        'Images/multi_images_smittenkitchen.json',
        'Images/one_image_zola.json',
    ];

    /**
     * @dataProvider returnFiles
     */
    public function testType($file)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals('image', $entity->getType());
        }
    }

    public function urlProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [
                    "http://farm8.staticflickr.com/7157/6720424335_71895e35cd.jpg",
                    "http://farm8.staticflickr.com/7158/6720417029_d5beee3350.jpg",
                    "http://farm8.staticflickr.com/7008/6720418205_1fe2f612bc.jpg",
                    "http://farm8.staticflickr.com/7151/6720419479_ed0c9f0381.jpg",
                    "http://farm8.staticflickr.com/7154/6720420525_bdfdc18085.jpg",
                    "http://farm8.staticflickr.com/7034/6720421771_c1f3ae7e66.jpg",
                    "http://farm8.staticflickr.com/7015/6720423151_be3e7da658.jpg",
                    "http://farm8.staticflickr.com/7029/6720426163_35b9272d04.jpg",
                    "http://farm8.staticflickr.com/7158/6720427093_27fde0c7cb.jpg"
                ]
            ],
            [
                'Images/one_image_zola.json',
                [
                    "https://drscdn.500px.org/photo/78703451/m%3D2048/956d2879591f57e2352d2064e98f461b"
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $images
     * @dataProvider urlProvider
     */
    public function testUrl($file, $images)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($images[$i], $entity->getUrl());
        }
    }

    public function anchorUrlProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [
                    "http://smittenkitchen.com:80/2012/01/buckwheat-baby-with-salted-caramel-syrup/",
                    "http://www.flickr.com:80/photos/smitten/6720417029/",
                    "http://www.flickr.com:80/photos/smitten/6720418205/",
                    "http://www.flickr.com:80/photos/smitten/6720419479/",
                    "http://www.flickr.com:80/photos/smitten/6720420525/",
                    "http://www.flickr.com:80/photos/smitten/6720421771/",
                    "http://www.flickr.com:80/photos/smitten/6720423151/",
                    "http://www.flickr.com:80/photos/smitten/6720426163/",
                    "http://www.flickr.com:80/photos/smitten/6720427093/"
                ]
            ],
            ['Images/one_image_zola.json', [null]]
        ];
    }

    /**
     * @param $file
     * @param $links
     * @dataProvider anchorUrlProvider
     */
    public function testAnchorUrl($file, $links)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($links[$i], $entity->getAnchorUrl());
        }
    }

    public function xpathProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[1]/A/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[4]/A[1]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[4]/A[2]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[6]/A/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[7]/A[1]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[7]/A[2]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[7]/A[3]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[9]/A[1]/IMG",
                    "/HTML/BODY/DIV[@id='page']/DIV[@id='content']/DIV[@id='post-8109']/DIV[@class='entry']/P[9]/A[2]/IMG"
                ]
            ],
            [
                'Images/one_image_zola.json',
                ["/HTML/BODY/DIV[@class='photo_show minimal has_next_photo has_previous_photo']/DIV[@class='photo segment']/DIV[@id='photo_78703451']/IMG[@class='the_photo']"]
            ]
        ];
    }

    /**
     * @param $file
     * @param $xpaths
     * @dataProvider xpathProvider
     */
    public function testXPath($file, $xpaths)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($xpaths[$i], $entity->getXPath());
        }
    }

    public function ocrProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                ["", "", "", "", "", "", "", "", ""]
            ],
            ['Images/one_image_zola.json', [""]]
        ];
    }

    /**
     * @param $file
     * @param $ocr
     * @dataProvider ocrProvider
     */
    public function testOcr($file, $ocr)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($ocr[$i], $entity->getOcr());
        }
    }

    public function facesProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                ["", "", "", "", "", "", "", "", ""]
            ],
            ['Images/one_image_zola.json', [""]]
        ];
    }

    /**
     * @param $file
     * @param $faces
     * @dataProvider facesProvider
     */
    public function testFaces($file, $faces)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($faces[$i], $entity->getFaces());
        }
    }

    public function mentionsProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [
                    [
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "Pancakes | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/category/pancakes/page/3/"
                        ],
                        [
                            "title" => "Recipes with Heavy Cream from smitten kitchen | Feastie",
                            "link" => "http://www.feastie.com/recipes-sources/smitten-kitchen/ingredients/heavy-cream-7161"
                        ],
                        [
                            "title" => "Carly | Life of Plenty on Pinterest",
                            "link" => "https://www.pinterest.com/lifeofplenty/"
                        ],
                        [
                            "title" => "Meredith Travis on Pinterest",
                            "link" => "https://www.pinterest.com/mltravis/"
                        ]
                    ],
                    [
                        [
                            "title" => "Meti P on Pinterest",
                            "link" => "https://www.pinterest.com/meti/"
                        ],
                        [
                            "title" => "Michelle Brainard on Pinterest",
                            "link" => "https://www.pinterest.com/mickiemo/"
                        ],
                        [
                            "title" => "apple sharlotka | Flickr - Photo Sharing!",
                            "link" => "https://www.flickr.com/photos/smitten/6647447061/"
                        ],
                        [
                            "title" => "caramel stages | Flickr - Photo Sharing!",
                            "link" => "https://www.flickr.com/photos/smitten/6720418205/"
                        ],
                        [
                            "title" => "the makings of caramel | Flickr - Photo Sharing!",
                            "link" => "https://www.flickr.com/photos/smitten/6720417029/"
                        ]
                    ],
                    [
                        [
                            "title" => "2012 January | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/"
                        ],
                        [
                            "title" => "2012 | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/page/12/"
                        ],
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "Pancakes | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/category/pancakes/page/3/"
                        ],
                        [
                            "title" => "Elizabeth Palazzolo on Pinterest",
                            "link" => "https://www.pinterest.com/elizabook/"
                        ]
                    ],
                    [
                        [
                            "title" => "2012 January | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/"
                        ],
                        [
                            "title" => "2012 | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/page/12/"
                        ],
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "Pancakes | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/category/pancakes/page/3/"
                        ],
                        [
                            "title" => "caramel stages | Flickr - Photo Sharing!",
                            "link" => "https://www.flickr.com/photos/smitten/6720418205/"
                        ]
                    ],
                    [
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "caramel stages | Flickr - Photo Sharing!",
                            "link" => "https://www.flickr.com/photos/smitten/6720418205/"
                        ],
                        [
                            "title" => "Блин с карамельным сиропом - пошаговый кулинарный ...",
                            "link" => "http://povar.ru/recipes/blin_s_karamelnym_siropom-6653.html"
                        ]
                    ],
                    [
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ]
                    ],
                    [
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "Блин с карамельным сиропом - пошаговый кулинарный ...",
                            "link" => "http://povar.ru/recipes/blin_s_karamelnym_siropom-6653.html"
                        ]
                    ],
                    [
                        [
                            "title" => "Rachelle Neame on Pinterest",
                            "link" => "https://www.pinterest.com/rneame/"
                        ],
                        [
                            "title" => "S'mores pie! by smitten, via Flickr | sweet | Pinterest",
                            "link" => "https://www.pinterest.com/pin/42854633928124979/"
                        ],
                        [
                            "title" => "Dondakaya Annam-Ivy Gourd Rice | Vegetarian meals ...",
                            "link" => "http://www.pinterest.com/pin/276901077062269843/"
                        ],
                        [
                            "title" => "Pin by Cheryl Gausdal on Gluten Free | Pinterest",
                            "link" => "http://www.pinterest.com/pin/37014028162142350/"
                        ],
                        [
                            "title" => "Pepe baby shoes from Caramel Baby & Child / baby time ...",
                            "link" => "http://www.juxtapost.com/site/permlink/b14ee390-11f0-11e2-9689-9fce36180d51/post/pepe_baby_shoes_from_caramel_baby_amp_child/"
                        ]
                    ],
                    [
                        [
                            "title" => "buckwheat baby with salted caramel syrup | smitten kitchen",
                            "link" => "http://smittenkitchen.com/blog/2012/01/buckwheat-baby-with-salted-caramel-syrup/"
                        ],
                        [
                            "title" => "Bosco on Pinterest",
                            "link" => "https://www.pinterest.com/boscosyrup/"
                        ],
                        [
                            "title" => "Urban Outfitters Archives - Glitter, Inc.Glitter, Inc.",
                            "link" => "http://glitterinc.com/tag/urban-outfitters/"
                        ],
                        [
                            "title" => "The World's Best Photos by smitten - Flickr Hive Mind",
                            "link" => "http://fiveprime.org/flickr_hvmnd.cgi?method=GET&page=24&photo_number=50&tag_mode=all&search_type=User&sorting=Interestingness&photo_type=75&noform=t&search_domain=User&sort=Interestingness&textinput=smitten"
                        ],
                        [
                            "title" => "Реборноправда-50 — Дежурка",
                            "link" => "http://pravdoruboklon.diary.ru/p177349995.htm"
                        ]
                    ]
                ]
            ],
            [
                'Images/one_image_zola.json',
                [
                    [
                        [
                            "title" => "Bruno Skvorc / 500px",
                            "link" => "https://500px.com/swader"
                        ],
                        [
                            "title" => "Top1Walls: Captain America Chris Evans Chris Hemsworth ...",
                            "link" => "http://top1walls.com/wallpaper/1735670-Captain-America-Chris-Evans-Chris-Hemsworth-Iron-Man-Marvel-Comics"
                        ],
                        [
                            "title" => "Kitten in the rain legs hand black cat white anim 1400x900",
                            "link" => "http://top1walls.com/wallpaper/2506-kitten-in-the-rain--legs-hand-black-cat-white-animal"
                        ],
                        [
                            "title" => "TOP1walls: Hund recht reizend süße Tiere Hunde Welpen ...",
                            "link" => "http://de.top1walls.com/wallpaper/2504-recht-reizend-s%C3%BC%C3%9Fe-Tiere"
                        ],
                        [
                            "title" => "해상도 - TOP1walls: desktop bakcgrounds",
                            "link" => "http://kr.top1walls.com/wallpaper/2506-"
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param $file
     * @param $mentions
     * @dataProvider mentionsProvider
     */
    public function testMentions($file, $mentions)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($mentions[$i], $entity->getMentions());
        }
    }

    public function heightProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [333, 333, 334, 333, 333, 333, 333, 333, 333]
            ],
            ['Images/one_image_zola.json', [1365]]
        ];
    }

    /**
     * @param $file
     * @param $dimensions
     * @dataProvider heightProvider
     */
    public function testHeight($file, $dimensions)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($dimensions[$i], $entity->getHeight());
        }
    }

    public function widthProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [500, 500, 500, 500, 500, 500, 500, 500, 500]
            ],
            ['Images/one_image_zola.json', [2048]]
        ];
    }

    /**
     * @param $file
     * @param $dimensions
     * @dataProvider widthProvider
     */
    public function testWidth($file, $dimensions)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($dimensions[$i], $entity->getWidth());
        }
    }

    public function naturalHeightProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [333, 333, 334, 333, 333, 333, 333, 333, 333]
            ],
            ['Images/one_image_zola.json', [1365]]
        ];
    }

    /**
     * @param $file
     * @param $dimensions
     * @dataProvider naturalHeightProvider
     */
    public function testNaturalHeight($file, $dimensions)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($dimensions[$i], $entity->getNaturalHeight());
        }
    }

    public function naturalWidthProvider()
    {
        return [
            [
                'Images/multi_images_smittenkitchen.json',
                [500, 500, 500, 500, 500, 500, 500, 500, 500]
            ],
            ['Images/one_image_zola.json', [2048]]
        ];
    }

    /**
     * @param $file
     * @param $dimensions
     * @dataProvider naturalWidthProvider
     */
    public function testNaturalWidth($file, $dimensions)
    {
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($dimensions[$i], $entity->getNaturalWidth());
        }
    }


}
