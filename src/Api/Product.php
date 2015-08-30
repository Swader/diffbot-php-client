<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Product extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'https://api.diffbot.com/v3/product';

    /**
     * If set to false, will not extract article comments in a Discussion
     * entity embedded in the Product entity. By default, it will.
     * @param bool $bool
     * @return $this
     */
    public function setDiscussion($bool = true)
    {
        $this->otherOptions['discussion'] = ($bool) ? 'true' : 'false';

        return $this;
    }

    /**
     * @see Swader\Diffbot\Entity\Product::getColors()
     * @param bool|mixed $bool
     * @return $this
     */
    public function setColors($bool)
    {
        $this->fieldSettings['colors'] = (bool)$bool;

        return $this;
    }

    /**
     * @see Swader\Diffbot\Entity\Product::getSize()
     * @param bool|mixed $bool
     * @return $this
     */
    public function setSize($bool)
    {
        $this->fieldSettings['size'] = (bool)$bool;

        return $this;
    }

    /**
     * @see Swader\Diffbot\Entity\Product::isAvailable()
     * @param bool|mixed $bool
     * @return $this
     */
    public function setAvailability($bool)
    {
        $this->fieldSettings['availability'] = (bool)$bool;

        return $this;
    }
}
