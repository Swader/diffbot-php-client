<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Job;

class JobCrawl extends Job
{
    /**
     * Maximum number of pages to crawl
     * @see http://support.diffbot.com/crawlbot/whats-the-difference-between-crawling-and-processing/
     *
     * @return int
     */
    public function getMaxToCrawl()
    {
        return (int)$this->data['maxToCrawl'];
    }

    /**
     * Maximum number of pages to process
     * @see http://support.diffbot.com/crawlbot/whats-the-difference-between-crawling-and-processing/
     *
     * @return int
     */
    public function getMaxToProcess()
    {
        return (int)$this->data['maxToProcess'];
    }

    /**
     * Whether or not the job was set to only process newly found links,
     * ignoring old but potentially updated ones
     *
     * @return bool
     */
    public function getOnlyProcessIfNew()
    {
        return (bool)$this->data['onlyProcessIfNew'];
    }

    /**
     * Seed URLs provided to the job. Always returned as array.
     *
     * @return array
     */
    public function getSeeds()
    {
        return explode(' ', $this->data['seeds']);
    }
}