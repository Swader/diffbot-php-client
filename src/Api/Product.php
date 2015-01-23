<?php

namespace Swader\Diffbot\Api;

use GuzzleHttp\Client;
use Swader\Diffbot\Abstracts\Api;

class Product extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/product';

    protected static $optionalFields = [
        'sku',
        'mpn',
        'shippingAmount',
        'saveAmount',
        'saveAmountDetails',
        'offerPriceDetails',
        'regularPriceDetails',
        'prefixCode',
        'productOrigin',
        'links',
        'meta',
        'querystring',
        'breadcrumb',
        'availability',
        'colors',
        'size'
    ];

    public static function getOptionalFields()
    {
        return self::$optionalFields;
    }

    public function call()
    {
    }
}
