<?php

namespace Swader\Diffbot\Abstracts;

use Swader\Diffbot\Exceptions\DiffbotException;

abstract class Job extends Entity
{

    /**
     * Returns the name of the crawljob
     * @return string
     */
    public function getName()
    {
        return (string)$this->data['name'];
    }

    /**
     * Should always return either "crawl" or "bulk"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * Timestamp of job creation
     *
     * @return int
     */
    public function getJobCreationTimeUTC()
    {
        return (isset($this->data['jobCreationTimeUTC']))
            ? (int)$this->data['jobCreationTimeUTC'] : null;
    }

    /**
     * Timestamp of job completion
     *
     * @return int
     */
    public function getJobCompletionTimeUTC()
    {
        return (isset($this->data['jobCompletionTimeUTC']))
            ? (int)$this->data['jobCompletionTimeUTC'] : null;
    }

    /**
     * Possible statuses
     *
     * 0    Job is initializing
     * 1    Job has reached maxRounds limit
     * 2    Job has reached maxToCrawl limit
     * 3    Job has reached maxToProcess limit
     * 4    Next round to start in _____ seconds
     * 5    No URLs were added to the crawl
     * 6    Job paused
     * 7    Job in progress
     * 8    All crawling temporarily paused by root administrator for maintenance.
     * 9    Job has completed and no repeat is scheduled
     *
     * @return array
     */
    public function getJobStatus()
    {
        return (isset($this->data['jobStatus']))
            ? $this->data['jobStatus'] : [];
    }

    /**
     * True or false, depending on whether "job complete" notification was sent
     *
     * @return bool
     */
    public function getNotificationSent()
    {
        return (bool)$this->data['sentJobDoneNotification'];
    }

    /**
     * Number of objects found
     *
     * @return int
     */
    public function getObjectsFound()
    {
        return (int)$this->data['objectsFound'];
    }

    /**
     * Number of URLs harvested
     *
     * @return int
     */
    public function getUrlsHarvested()
    {
        return (int)$this->data['urlsHarvested'];
    }

    /**
     * Returns an array with information about crawls - total attempts,
     * successes, and successes this round
     *
     * @return array
     */
    public function getPageCrawlInfo()
    {
        return [
            'attempts' => $this->data['pageCrawlAttempts'],
            'successes' => $this->data['pageCrawlSuccesses'],
            'successesThisRound' => $this->data['pageCrawlSuccessesThisRound']
        ];
    }

    /**
     * Returns an array with information about crawls - total attempts,
     * successes, and successes this round
     *
     * @return array
     */
    public function getPageProcessInfo()
    {
        return [
            'attempts' => $this->data['pageProcessAttempts'],
            'successes' => $this->data['pageProcessSuccesses'],
            'successesThisRound' => $this->data['pageProcessSuccessesThisRound']
        ];
    }

    /**
     * The maximum number of crawl repeats. By default (maxRounds=0) repeating
     * crawls will continue indefinitely.
     *
     * @return int
     */
    public function getMaxRounds()
    {
        return (int)$this->data['maxRounds'];
    }

    /**
     * The number of days as a floating-point (e.g. repeat=7.0) to repeat this
     * crawl. By default crawls will not be repeated.
     *
     * @return float
     */
    public function getRepeat()
    {
        return (float)$this->data['repeat'];
    }

    /**
     * Wait this many seconds between each URL crawled from a single IP address.
     * Number of seconds as an integer or floating-point number
     * (e.g., crawlDelay=0.25).
     *
     * @return float
     */
    public function getCrawlDelay()
    {
        return (float)$this->data['crawlDelay'];
    }

    /**
     * Whether or not the job was set to respect robots.txt
     *
     * @return bool
     */
    public function getObeyRobots()
    {
        return (bool)$this->data['obeyRobots'];
    }

    /**
     * How many rounds were completed with the job so far
     *
     * @return int
     */
    public function getRoundsCompleted()
    {
        return (int)$this->data['roundsCompleted'];
    }

    /**
     * Returns timestamp of when next crawl round is about to start or 0 if none
     *
     * @return int
     */
    public function getRoundStartTime()
    {
        return (int)$this->data['roundStartTime'];
    }

    /**
     * Returns timestamp of current time
     *
     * @return int
     */
    public function getCurrentTime()
    {
        return (int)$this->data['currentTime'];
    }

    /**
     * Returns timestamp of current time, UTC.
     * Should be the same as getCurrentTime
     *
     * @return int
     */
    public function getCurrentTimeUTC()
    {
        return (int)$this->data['currentTimeUTC'];
    }

    /**
     * The API URL is the URL of the API used to process pages found in the
     * crawl. If the job was created with this Diffbot lib, then it was
     * automatically built from a pre-configured API instance
     *
     * The API URL will be URL decoded, whereas it is submitted encoded.
     *
     * @return string
     */
    public function getApiUrl()
    {
        return (string)$this->data['apiUrl'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setUrlCrawlPattern
     * @return string
     */
    public function getUrlCrawlPattern()
    {
        return (string)$this->data['urlCrawlPattern'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setUrlProcessPattern
     * @return string
     */
    public function getUrlProcessPattern()
    {
        return (string)$this->data['urlProcessPattern'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setPageProcessPattern
     * @return string
     */
    public function getPageProcessPattern()
    {
        return (string)$this->data['pageProcessPattern'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setUrlCrawlRegex
     *
     * @return string
     */
    public function getUrlCrawlRegex()
    {
        return (string)$this->data['urlCrawlRegEx'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setUrlProcessRegex
     *
     * @return string
     */
    public function getUrlProcessRegex()
    {
        return (string)$this->data['urlProcessRegEx'];
    }

    /**
     * @see \Swader\Diffbot\Api\Crawl::setMaxHops
     *
     * @return int
     */
    public function getMaxHops()
    {
        return (int)$this->data['maxHops'];
    }

    /**
     * Returns the link to the dataset the job produced.
     *
     * Accepted arguments are: "json", "csv" and "debug".
     * It is important to be aware of the difference between the types.
     * See "Retrieving Bulk Data" in link.
     *
     * @see https://www.diffbot.com/dev/docs/crawl/api.jsp
     *
     * @param string $type
     * @return string
     * @throws DiffbotException
     */
    public function getDownloadUrl($type = "json")
    {
        switch ($type) {
            case "json":
                return $this->data['downloadJson'];
            case "debug":
                return $this->data['downloadUrls'];
            case "csv":
                return rtrim($this->data['downloadJson'], '.json') . '.csv';
            default:
                break;
        }

        throw new \InvalidArgumentException(
            'Only json, debug, or csv download link available. You asked for: '
            . $type);
    }

    /**
     * Returns the email that was set to be notified after job's completion
     *
     * @return string
     */
    public function getNotifyEmail()
    {
        return (string)$this->data['notifyEmail'];
    }

    /**
     * Returns the webhook that was set to be pinged after job's completion
     *
     * @return string
     */
    public function getNotifyWebhook()
    {
        return (string)$this->data['notifyWebhook'];
    }
}