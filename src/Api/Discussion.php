<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Discussion extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/discussion';

    /**
     * Set the maximum number of pages in a thread to automatically concatenate
     * in a single response. Default = 1 (no concatenation). Set maxPages=all
     * to retrieve all pages of a thread regardless of length. Each individual
     * page will count as a separate API call.
     *
     * @param int|string $max Integer or "all"
     * @return $this
     */
    public function setMaxPages($max = 1)
    {
        if ($max == 'all' || is_numeric($max)) {
            $this->otherOptions['maxPages'] = $max;
        }

        return $this;
    }

    /**
     * @see Swader\Diffbot\Entity\Discussion::getSentiment()
     * @param $bool
     * @return $this
     */
    public function setSentiment($bool)
    {
        $this->fieldSettings['sentiment'] = (bool)$bool;

        return $this;
    }

}
