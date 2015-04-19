<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Analyze extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/analyze';

    /**
     * If set to false, will not extract article comments in a Discussion
     * entity embedded in the Article / Product entity. By default, it will.
     * @param bool $bool
     * @return $this
     */
    public function setDiscussion($bool = true)
    {
        $this->otherOptions['discussion'] = ($bool) ? 'true' : 'false';

        return $this;
    }

    /**
     * By default the Analyze API will fully extract all pages that match an
     * existing Automatic API -- articles, products or image pages. Set mode
     * to a specific page-type (e.g., mode=article) to extract content only
     * from that specific page-type. All other pages will simply return the
     * default Analyze fields.
     *
     * @param string $mode article, product or image
     * @return $this
     */
    public function setMode($mode)
    {
        if (!in_array($mode, ['article', 'product', 'image'])) {
            $error = 'Only "article", "product" and "image" modes supported.';
            throw new \InvalidArgumentException($error);
        }
        $this->otherOptions['mode'] = $mode;

        return $this;
    }

}
