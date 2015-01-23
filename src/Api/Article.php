<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;

class Article extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/article';

    protected static $optionalFields = [
        'links',
        'meta',
        'querystring'
    ];

    public static function getOptionalFields()
    {
        return self::$optionalFields;
    }

    public function setImagesHeight($bool = null)
    {
        $this->fieldSettings['images(height)'] = ($bool) ? (bool)$bool : false;
        return $this;
    }

    public function getImagesHeight()
    {
        return (isset($this->fieldSettings['images(height)'])) ? $this->fieldSettings['images(height)'] : false;
    }

    public function setImagesWidth($bool = null)
    {
        $this->fieldSettings['images(width)'] = ($bool) ? (bool)$bool : false;
        return $this;
    }

    public function getImagesWidth()
    {
        return (isset($this->fieldSettings['images(width)'])) ? $this->fieldSettings['images(width)'] : false;
    }

    public function setVideosNaturalHeight($bool = null)
    {
        $this->fieldSettings['videos(naturalHeight)'] = ($bool) ? (bool)$bool : false;
        return $this;
    }

    public function getVideosNaturalHeight()
    {
        return (isset($this->fieldSettings['videos(naturalHeight)']))
            ? $this->fieldSettings['videos(naturalHeight)'] : false;
    }

    public function setVideosNaturalWidth($bool = null)
    {
        $this->fieldSettings['videos(naturalWidth)'] = ($bool) ? (bool)$bool : false;
        return $this;
    }

    public function getVideosNaturalWidth()
    {
        return (isset($this->fieldSettings['videos(naturalWidth)']))
            ? $this->fieldSettings['videos(naturalWidth)'] : false;
    }

    public function call()
    {

    }
}
