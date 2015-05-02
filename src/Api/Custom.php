<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;

class Custom extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3';

    public function __construct($url, $name)
    {
        parent::__construct($url);
        $this->apiUrl .= '/' . trim($name);
    }
}
