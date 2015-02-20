<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Traits\StandardApi;

class Article extends Api
{
    use StandardApi;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3/article';
}
