<?php

namespace Swader\Diffbot\Test\Entity;

use GuzzleHttp\Message\ResponseInterface;
use Swader\Diffbot\Entity\EntityIterator;
use Swader\Diffbot\Entity\Image;
use Swader\Diffbot\Entity\JobCrawl as Job;
use Swader\Diffbot\Test\ResponseProvider;

class CrawlJobTest extends ResponseProvider
{
    /** @var  array */
    protected $responses = [];

    protected $files = [
        'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
        'Crawlbot/15-05-20/multiplejobs01.json'
    ];

    protected function ei($file)
    {
        $this->prepareResponses();
        /** @var ResponseInterface $response */
        $response = $this->responses[$file];
        $jobs = [];
        foreach ($response->json()['jobs'] as $data) {
            $jobs[] = new Job($data);
        }

        return new EntityIterator($jobs, $response);
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
        /** @var Image $entity */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals('crawl', $entity->getType());
        }
    }

    public function nameProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 'sitepoint_01']
        ];
    }

    /**
     * @dataProvider nameProvider
     * @param $file
     * @param $input
     */
    public function testName($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getName());
        }
    }

    public function timeProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    1431865254,
                    1431928375,
                    1431981899,
                    1431981899
                ]
            ]
        ];
    }

    /**
     * @dataProvider timeProvider
     * @param $file
     * @param $input
     */
    public function testTime($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input[0], $entity->getJobCreationTimeUTC());
            $this->assertEquals($input[1], $entity->getJobCompletionTimeUTC());
            $this->assertEquals($input[2], $entity->getCurrentTime());
            $this->assertEquals($input[3], $entity->getCurrentTimeUTC());
        }
    }

    public function statusProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    "status" => 2,
                    "message" => "Job has reached maxToCrawl limit."
                ]
            ]
        ];
    }

    /**
     * @dataProvider statusProvider
     * @param $file
     * @param $input
     */
    public function testStatus($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getJobStatus());
        }
    }

    public function notificationSentProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 1]
        ];
    }

    /**
     * @dataProvider notificationSentProvider
     * @param $file
     * @param $input
     */
    public function testNotificationSent($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getNotificationSent());
        }
    }

    public function countProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    91500,
                    3219125,
                    107872,
                    100000,
                    100000,
                    91957,
                    91500,
                    91500
                ]
            ]
        ];
    }

    /**
     * @dataProvider countProvider
     * @param $file
     * @param $input
     */
    public function testCounts($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input[0], $entity->getObjectsFound());
            $this->assertEquals($input[1], $entity->getUrlsHarvested());
            $this->assertEquals($input[2],
                $entity->getPageCrawlInfo()['attempts']);
            $this->assertEquals($input[3],
                $entity->getPageCrawlInfo()['successes']);
            $this->assertEquals($input[4],
                $entity->getPageCrawlInfo()['successesThisRound']);
            $this->assertEquals($input[5],
                $entity->getPageProcessInfo()['attempts']);
            $this->assertEquals($input[6],
                $entity->getPageProcessInfo()['successes']);
            $this->assertEquals($input[7],
                $entity->getPageProcessInfo()['successesThisRound']);
        }
    }

    public function maxRoundsProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', -1]
        ];
    }

    /**
     * @dataProvider maxRoundsProvider
     * @param $file
     * @param $input
     */
    public function testMaxRounds($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getMaxRounds());
        }
    }

    public function repeatProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 0]
        ];
    }

    /**
     * @dataProvider repeatProvider
     * @param $file
     * @param $input
     */
    public function testRepeat($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getRepeat());
        }
    }

    public function crawlDelayProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 0.25]
        ];
    }

    /**
     * @dataProvider crawlDelayProvider
     * @param $file
     * @param $input
     */
    public function testDelay($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getCrawlDelay());
        }
    }

    public function obeyRobotsProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 1]
        ];
    }

    /**
     * @dataProvider obeyRobotsProvider
     * @param $file
     * @param $input
     */
    public function testObeyRobots($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getObeyRobots());
        }
    }

    public function maxProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 100000, 100000]
        ];
    }

    /**
     * @dataProvider maxProvider
     * @param $file
     * @param $input1
     * @param $input2
     */
    public function testMax($file, $input1, $input2)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input1, $entity->getMaxToCrawl());
            $this->assertEquals($input2, $entity->getMaxToProcess());
        }
    }

    public function processIfNewProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 1]
        ];
    }

    /**
     * @dataProvider processIfNewProvider
     * @param $file
     * @param $input
     */
    public function testProcessNew($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getOnlyProcessIfNew());
        }
    }

    public function seedsProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                ['http://sitepoint.com']
            ]
        ];
    }

    /**
     * @dataProvider seedsProvider
     * @param $file
     * @param $input
     */
    public function testSeeds($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getSeeds());
        }
    }

    public function roundsCompletedProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 0]
        ];
    }

    /**
     * @dataProvider roundsCompletedProvider
     * @param $file
     * @param $input
     */
    public function testRoundsCompleted($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getRoundsCompleted());
        }
    }

    public function roundStartTimeProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 0]
        ];
    }

    /**
     * @dataProvider roundStartTimeProvider
     * @param $file
     * @param $input
     */
    public function testRoundStartTime($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getRoundStartTime());
        }
    }

    public function apiUrlProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                'http://api.diffbot.com/v3/article?&discussion=false'
            ]
        ];
    }

    /**
     * @dataProvider apiUrlProvider
     * @param $file
     * @param $input
     */
    public function testApiUrl($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getApiUrl());
        }
    }

    public function patternProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    '',
                    '',
                    ''
                ]
            ]
        ];
    }

    /**
     * @dataProvider patternProvider
     * @param $file
     * @param $input
     */
    public function testPatterns($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input[0], $entity->getUrlCrawlPattern());
            $this->assertEquals($input[1], $entity->getUrlProcessPattern());
            $this->assertEquals($input[2], $entity->getPageProcessPattern());
        }
    }

    public function regexProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    '',
                    ''
                ]
            ]
        ];
    }

    /**
     * @dataProvider regexProvider
     * @param $file
     * @param $input
     */
    public function testRegex($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input[0], $entity->getUrlCrawlRegex());
            $this->assertEquals($input[1], $entity->getUrlProcessRegex());
        }
    }

    public function maxHopsProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', -1]
        ];
    }

    /**
     * @dataProvider maxHopsProvider
     * @param $file
     * @param $input
     */
    public function testMaxHops($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input, $entity->getMaxHops());
        }
    }

    public function downloadProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                [
                    'json' => 'http://api.diffbot.com/v3/crawl/download/xxxxxxxxxxx-sitepoint_01_data.json',
                    'csv' => 'http://api.diffbot.com/v3/crawl/download/xxxxxxxxxxx-sitepoint_01_data.csv',
                    'debug' => 'http://api.diffbot.com/v3/crawl/download/xxxxxxxxxxx-sitepoint_01_urls.csv'
                ]
            ]
        ];
    }

    /**
     * @dataProvider downloadProvider
     * @param $file
     * @param $input
     */
    public function testDownload($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input['json'], $entity->getDownloadUrl());
            $this->assertEquals($input['json'],
                $entity->getDownloadUrl('json'));
            $this->assertEquals($input['csv'], $entity->getDownloadUrl('csv'));
            $this->assertEquals($input['debug'],
                $entity->getDownloadUrl('debug'));
        }
    }

    public function notifyProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', ['', '']]
        ];
    }

    /**
     * @dataProvider notifyProvider
     * @param $file
     * @param $input
     */
    public function testNotify($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->assertEquals($input[0], $entity->getNotifyEmail());
            $this->assertEquals($input[1], $entity->getNotifyWebhook());
        }
    }

    public function downloadFailProvider()
    {
        return [
            [
                'Crawlbot/15-05-18/sitepoint_01_maxCrawled.json',
                'wrongkey'
            ]
        ];
    }

    /**
     * @dataProvider downloadFailProvider
     * @param $file
     * @param $input
     * @throws \Swader\Diffbot\Exceptions\DiffbotException
     */
    public function testDownloadFail($file, $input)
    {
        /**
         * @var int $i
         * @var Job $entity
         */
        foreach ($this->ei($file) as $i => $entity) {
            $this->setExpectedException('InvalidArgumentException');
            $entity->getDownloadUrl($input);
        }
    }

    public function jobCountProvider()
    {
        return [
            ['Crawlbot/15-05-18/sitepoint_01_maxCrawled.json', 1],
            ['Crawlbot/15-05-20/multiplejobs01.json', 2]
        ];
    }

    /**
     * @dataProvider jobCountProvider
     * @param $file
     * @param $input
     */
    public function testCount($file, $input)
    {
        $this->assertEquals($input, $this->ei($file)->count());
    }
}
