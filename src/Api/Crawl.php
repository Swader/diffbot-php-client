<?php

namespace Swader\Diffbot\Api;

use \InvalidArgumentException;
use Swader\Diffbot\Entity\EntityIterator;
use Swader\Diffbot\Entity\JobCrawl;
use Swader\Diffbot\Exceptions\DiffbotException;
use Swader\Diffbot\Interfaces\Api;
use Swader\Diffbot\Traits\DiffbotAware;

/**
 * Class Crawl
 * @see https://www.diffbot.com/dev/docs/crawl/
 * @package Swader\Diffbot\Api
 */
class Crawl
{
    use DiffbotAware;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'https://api.diffbot.com/v3/crawl';

    /** @var string */
    protected $name;

    /** @var Api Api which should be used to process the pages */
    protected $api;

    /** @var array Options to set while initiating the API call */
    protected $otherOptions = [];

    /** @var array Array of seed URLs to crawl */
    protected $seeds = [];

    /**
     * @see getName
     * @param string|null $name
     * @param null|Api $api
     */
    public function __construct($name = null, Api $api = null)
    {
        if ($name !== null) {
            $this->name = $name;
            if ($api) {
                $this->setApi($api);
            }
        }
    }

    /**
     * Returns the unique name of the crawljob
     * This name is later used to download datasets, or to modify the job
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * API which should be used to process the pages
     *
     * Accepts a fully formed instance of any other API. Will use it to build
     * and auto-encode the URL. To satisfy the required $url param of the API
     * classes, use the string 'crawl' which prepares the API for Crawlbot
     * consumption internally.
     *
     * @see https://www.diffbot.com/dev/docs/crawl/api.jsp ApiUrl docs
     * @param Api $api
     * @return $this
     */
    public function setApi(Api $api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * An array of URLs (seeds) which to crawl for matching links
     *
     * By default Crawlbot will restrict spidering to the entire domain
     * ("http://blog.diffbot.com" will include URLs at "http://www.diffbot.com").
     *
     * @param array $seeds
     * @return $this
     */
    public function setSeeds(array $seeds)
    {
        $invalidSeeds = [];
        foreach ($seeds as $seed) {
            if (!filter_var($seed, FILTER_VALIDATE_URL)) {
                $invalidSeeds[] = $seed;
            }
        }
        if (!empty($invalidSeeds)) {
            throw new \InvalidArgumentException(
                'Some seeds were invalid: ' . implode(',', $invalidSeeds)
            );
        }

        $this->seeds = $seeds;

        return $this;
    }

    /**
     * Array of strings to limit pages crawled to those whose URLs
     * contain any of the content strings.
     *
     * You can use the exclamation point to specify a negative string, e.g.
     * !product to exclude URLs containing the string "product," and the ^ and
     * $ characters to limit matches to the beginning or end of the URL.
     *
     * The use of a urlCrawlPattern will allow Crawlbot to spider outside of
     * the seed domain; it will follow all matching URLs regardless of domain.
     *
     * @param array $pattern
     * @return $this
     */
    public function setUrlCrawlPatterns(array $pattern = null)
    {
        $this->otherOptions['urlCrawlPattern'] = ($pattern === null) ? null
            : implode("||", array_map(function ($item) {
                return urlencode($item);
            }, $pattern));

        return $this;
    }

    /**
     * Specify a regular expression to limit pages crawled to those URLs that
     * match your expression. This will override any urlCrawlPattern value.
     *
     * The use of a urlCrawlRegEx will allow Crawlbot to spider outside of the
     * seed domain; it will follow all matching URLs regardless of domain.
     *
     * @param string $regex
     * @return $this
     */
    public function setUrlCrawlRegEx($regex)
    {
        $this->otherOptions['urlCrawlRegEx'] = $regex;

        return $this;
    }

    /**
     * Specify ||-separated strings to limit pages processed to those whose
     * URLs contain any of the content strings.
     *
     * You can use the exclamation point to specify a negative string, e.g.
     * !/category to exclude URLs containing the string "/category," and the ^
     * and $ characters to limit matches to the beginning or end of the URL.
     *
     * @param array $pattern
     * @return $this
     */
    public function setUrlProcessPatterns(array $pattern = null)
    {
        $this->otherOptions['urlProcessPattern'] = ($pattern === null) ? null
            : implode("||", array_map(function ($item) {
                return urlencode($item);
            }, $pattern));

        return $this;
    }

    /**
     * Specify a regular expression to limit pages processed to those URLs that
     * match your expression. This will override any urlProcessPattern value.
     *
     * @param string $regex
     * @return $this
     */
    public function setUrlProcessRegEx($regex)
    {
        $this->otherOptions['urlProcessRegEx'] = $regex;

        return $this;

    }

    /**
     * Specify ||-separated strings to limit pages processed to those whose
     * HTML contains any of the content strings.
     *
     * @param array $pattern
     * @return $this
     */
    public function setPageProcessPatterns(array $pattern)
    {
        $this->otherOptions['pageProcessPattern'] = implode("||",
            array_map(function ($item) {
                return urlencode($item);
            }, $pattern));

        return $this;
    }

    /**
     * Specify the depth of your crawl. A maxHops=0 will limit processing to
     * the seed URL(s) only -- no other links will be processed; maxHops=1 will
     * process all (otherwise matching) pages whose links appear on seed URL(s);
     * maxHops=2 will process pages whose links appear on those pages; and so on
     *
     * By default, Crawlbot will crawl and process links at any depth.
     *
     * @param int $input
     * @return $this
     */
    public function setMaxHops($input = -1)
    {
        if ((int)$input < -1) {
            $input = -1;
        }
        $this->otherOptions['maxHops'] = (int)$input;

        return $this;
    }

    /**
     * Specify max pages to spider. Default: 100,000.
     *
     * @param int $input
     * @return $this
     */
    public function setMaxToCrawl($input = 100000)
    {
        if ((int)$input < 1) {
            $input = 1;
        }
        $this->otherOptions['maxToCrawl'] = (int)$input;

        return $this;
    }

    /**
     * Specify max pages to process through Diffbot APIs. Default: 100,000.
     *
     * @param int $input
     * @return $this
     */
    public function setMaxToProcess($input = 100000)
    {
        if ((int)$input < 1) {
            $input = 1;
        }
        $this->otherOptions['maxToProcess'] = (int)$input;

        return $this;
    }

    /**
     * If input is email address, end a message to this email address when the
     * crawl hits the maxToCrawl or maxToProcess limit, or when the crawl
     * completes.
     *
     * If input is URL, you will receive a POST with X-Crawl-Name and
     * X-Crawl-Status in the headers, and the full JSON response in the
     * POST body.
     *
     * @param string $string
     * @return $this
     * @throws InvalidArgumentException
     */
    public function notify($string)
    {
        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $this->otherOptions['notifyEmail'] = $string;

            return $this;
        }
        if (filter_var($string, FILTER_VALIDATE_URL)) {
            $this->otherOptions['notifyWebhook'] = urlencode($string);

            return $this;
        }

        throw new InvalidArgumentException(
            'Only valid email or URL accepted! You provided: ' . $string
        );
    }

    /**
     * Wait this many seconds between each URL crawled from a single IP address.
     * Specify the number of seconds as an integer or floating-point number.
     *
     * @param float $input
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setCrawlDelay($input = 0.25)
    {
        if (!is_numeric($input)) {
            throw new InvalidArgumentException('Input must be numeric.');
        }
        $input = ($input < 0) ? 0.25 : $input;
        $this->otherOptions['crawlDelay'] = (float)$input;

        return $this;
    }

    /**
     * Specify the number of days as a floating-point (e.g. repeat=7.0) to
     * repeat this crawl. By default crawls will not be repeated.
     *
     * @param int|float $input
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setRepeat($input)
    {
        if (!is_numeric($input) || !$input) {
            throw new \InvalidArgumentException('Only positive numbers allowed.');
        }
        $this->otherOptions['repeat'] = (float)$input;

        return $this;
    }

    /**
     * By default repeat crawls will only process new (previously unprocessed)
     * pages. Set to 0 to process all content on repeat crawls.
     *
     * @param int $int
     * @return $this
     */
    public function setOnlyProcessIfNew($int = 1)
    {
        $this->otherOptions['onlyProcessIfNew'] = (int)(bool)$int;

        return $this;
    }

    /**
     * Specify the maximum number of crawl repeats. By default (maxRounds=0)
     * repeating crawls will continue indefinitely.
     *
     * @param int $input
     * @return $this
     */
    public function setMaxRounds($input = 0)
    {
        if ((int)$input < -1) {
            $input = -1;
        }

        $this->otherOptions['maxRounds'] = (int)$input;

        return $this;
    }

    /**
     * Ignores robots.txt if set to 0/false
     *
     * @param bool $bool
     * @return $this
     */
    public function setObeyRobots($bool = true)
    {
        $this->otherOptions['obeyRobots'] = (int)(bool)$bool;

        return $this;
    }

    /**
     * Set value to 1 to force the use of proxy IPs for the crawl.
     *
     * @param bool $bool
     * @return $this
     */
    public function setUseProxies($bool = true)
    {
        $this->otherOptions['useProxies'] = (int)(bool)$bool;

        return $this;
    }

    /**
     * Force the start of a new crawl "round" (manually repeat the crawl).
     * If onlyProcessIfNew is set to 1 (default), only newly-created pages will
     * be processed.
     *
     * @param bool $commit
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function roundStart($commit = true)
    {
        $this->otherOptions = ['roundStart' => 1];

        return ($commit) ? $this->call() : $this;
    }

    /**
     * Pause a crawl.
     *
     * @param bool $commit
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function pause($commit = true)
    {
        $this->otherOptions = ['pause' => 1];

        return ($commit) ? $this->call() : $this;
    }

    /**
     * Pause a crawl.
     *
     * @param bool $commit
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function unpause($commit = true)
    {
        $this->otherOptions = ['pause' => 0];

        return ($commit) ? $this->call() : $this;
    }

    /**
     * Restart removes all crawled data while maintaining crawl settings.
     *
     * @param bool $commit
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function restart($commit = true)
    {
        $this->otherOptions = ['restart' => 1];

        return ($commit) ? $this->call() : $this;
    }

    /**
     * Delete a crawl, and all associated data, completely.
     *
     * @param bool $commit
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function delete($commit = true)
    {
        $this->otherOptions = ['delete' => 1];

        return ($commit) ? $this->call() : $this;
    }
    public function getCrawl()
    {
        $theUrl = $this->apiUrl ."?token=" . $this->diffbot->getToken() . "&name=" . $this->name;
        $response = $this->diffbot->getHttpClient()->get($theUrl);

        $array = json_decode($response->getBody(), true);

        if (isset($array['jobs'])) {
            $jobs = [];
            foreach ($array['jobs'] as $job) {
                $jobs[] = new JobCrawl($job);
            }
            return new EntityIterator($jobs, $response);
        } elseif (!isset($array['jobs']) && isset($array['response'])) {
            return $array['response'];
        } else {
            throw new DiffbotException($array["error"]);
        }
    }

    public function call()
    {
        $theHeader=["content-type"=>"application/x-www-form-urlencoded; charset=UTF-8"];
        $response = $this->diffbot->getHttpClient()->post($this->apiUrl, $theHeader, $this->buildUrl());


        $array = json_decode($response->getBody(), true);

        if (isset($array['jobs'])) {
            $jobs = [];
            foreach ($array['jobs'] as $job) {
                $jobs[] = new JobCrawl($job);
            }

            return new EntityIterator($jobs, $response);
        } elseif (!isset($array['jobs']) && isset($array['response'])) {
            return $array['response'];
        } else {
            throw new DiffbotException('It appears something went wrong - no data was returned. Did you use the correct token / job name?');
        }
    }

    /**
     * Builds out the URL string that gets requested once `call()` is called
     *
     * @return string
     */
    public function buildUrl()
    {

        if (isset($this->otherOptions['urlProcessRegEx'])
            && !empty($this->otherOptions['urlProcessRegEx'])
        ) {
            unset($this->otherOptions['urlProcessPattern']);
        }

        if (isset($this->otherOptions['urlCrawlRegEx'])
            && !empty($this->otherOptions['urlCrawlRegEx'])
        ) {
            unset($this->otherOptions['urlCrawlPattern']);
        }


        // Add token
        $url = 'token=' . $this->diffbot->getToken();

        if ($this->getName()) {
            // Add name
            $url .= '&name=' . $this->getName();

            // Add seeds
            if (!empty($this->seeds)) {
                $url .= '&seeds=' . implode('%20', array_map(function ($item) {
                        return urlencode($item);
                    }, $this->seeds));
            }

            // Add other options
            if (!empty($this->otherOptions)) {
                foreach ($this->otherOptions as $option => $value) {
                    $url .= '&' . $option . '=' . $value;
                }
            }

            // Add API link
            $url .= '&apiUrl=' . $this->getApiString();
        }

        return $url;
    }

    /**
     * Sets the request type to "urls" to retrieve the URL Report
     * URL for understanding diagnostic data of URLs
     *
     * @return $this
     */
    public function getUrlReportUrl($num = null)
    {
        $this->otherOptions['type'] = 'urls';

        if (!empty($num) && is_numeric($num)) {
            $this->otherOptions['num'] = $num;
        }

        // Setup data endpoint
        $url = $this->apiUrl . '/data';

        // Add token
        $url .= '?token=' . $this->diffbot->getToken();

        if ($this->getName()) {
            // Add name
            $url .= '&name=' . $this->getName();

            // Add other options
            if (!empty($this->otherOptions)) {
                foreach ($this->otherOptions as $option => $value) {
                    $url .= '&' . $option . '=' . $value;
                }
            }
        }

        return $url;

    }

    /**
     * @return string
     */
    protected function getApiString()
    {
        if (!$this->api) {
            $this->api = $this->diffbot->createAnalyzeAPI('crawl');
            $this->api->setMode('auto');
        }

        return urlencode($this->api->buildUrl());
    }
}
