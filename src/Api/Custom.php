<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;

class Custom extends Api
{
    /** @var string API URL to which to send the request */
    protected $apiUrl = 'http://api.diffbot.com/v3';

    public function __construct($url, $name)
    {

        /*
        @todo Throw exception for invalid names.
        Diffbot HQ will provide regex for invalid chars in API names. Once
        done, modify this case to throw exceptions for invalid ones, and write
        test cases.

        Note that all API names with ? and / in their name currently fail to
        execute in the Diffbot test runner, so it's questionable whether they're
        even supposed to be supported.
        */

        parent::__construct($url);
        $this->apiUrl .= '/' . trim($name);
    }
}
