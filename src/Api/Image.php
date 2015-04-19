<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Image extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/image';

    /**
     * Tells the API call to return the mentions field
     * @see https://www.diffbot.com/dev/docs/image/
     * @param $bool
     * @return $this
     */
    public function setMentions($bool)
    {
        $this->fieldSettings['mentions'] = (bool)$bool;
        return $this;
    }

    /**
     * Sets the API call to return the faces field
     * @see https://www.diffbot.com/dev/docs/image/
     * @param $bool
     * @return $this
     */
    public function setFaces($bool)
    {
        $this->fieldSettings['faces'] = (bool)$bool;
        return $this;
    }

    /**
     * Sets the API call to return the ocr field.
     * @see https://www.diffbot.com/dev/docs/image/
     * @param $bool
     * @return $this
     */
    public function setOcr($bool)
    {
        $this->fieldSettings['ocr'] = (bool)$bool;
        return $this;
    }
}
