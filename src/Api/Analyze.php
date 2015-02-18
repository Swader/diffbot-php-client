<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;

class Analyze extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/analyze';

    protected static $optionalFields = [
        'links',
        'meta',
        'querystring'
    ];

    public static function getOptionalFields()
    {
        return self::$optionalFields;
    }
}
