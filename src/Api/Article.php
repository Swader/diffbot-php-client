<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Article extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/article';

    /**
     * @see Swader\Diffbot\Entity\Article::getSentiment()
     * @param $bool
     * @return $this
     */
    public function setSentiment($bool)
    {
        $this->fieldSettings['sentiment'] = (bool)$bool;

        return $this;
    }

    /**
     * If set to false, Diffbot will not auto-concat several pages of a
     * multi-page article into one. Defaults to true, max 20 pages.
     *
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @param bool $bool
     * @return $this
     */
    public function setPaging($bool = true)
    {
        $this->otherOptions['paging'] = ($bool) ? 'true' : 'false';

        return $this;
    }

    /**
     * Set the maximum number of automatically-generated tags to return.
     * By default a maximum of five tags will be returned.
     * @param int $max
     * @return $this
     */
    public function setMaxTags($max = 5)
    {
        $this->otherOptions['maxTags'] = (int)$max;

        return $this;
    }

    /**
     * If set to false, will not extract article comments in a Discussion
     * entity embedded in the Article entity. By default, it will.
     * @param bool $bool
     * @return $this
     */
    public function setDiscussion($bool = true)
    {
        $this->otherOptions['discussion'] = ($bool) ? 'true' : 'false';

        return $this;
    }
}
