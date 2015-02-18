<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;

class Image extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/image';

    protected static $optionalFields = [
        'height',
        'width',
        'links',
        'meta',
        'querystring',
        'breadcrumb',

        'mentions',
        'ocr',
        'faces'
    ];

    public static function getOptionalFields()
    {
        return self::$optionalFields;
    }
}
