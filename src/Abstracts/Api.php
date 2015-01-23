<?php

namespace Swader\Diffbot\Abstracts;

use Swader\Diffbot\Exceptions\DiffbotException;

/**
 * Class Api
 * @package Swader\Diffbot\Abstracts
 */
abstract class Api implements \Swader\Diffbot\Interfaces\Api
{
    /** @var int Timeout value in ms - defaults to 30s if empty */
    private $timeout = 30000;

    /** @var string The URL onto which to unleash the API in question */
    private $url;

    /** @var string API URL to which to send the request */
    protected $apiUrl;

    /** @var array Settings on which optional fields to fetch */
    protected $fieldSettings = [];

    public function __construct($url)
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException('URL param must be a string.');
        }
        $url = trim($url);
        if (strlen($url) < 4) {
            throw new \InvalidArgumentException('URL must be at least four characters in length');
        }
        if ($parts = parse_url($url)) {
            if (!isset($parts["scheme"])) {
                $url = "http://$url";
            }
        }
        $filtered_url = filter_var($url, FILTER_VALIDATE_URL);
        if (false === $filtered_url) {
            throw new \InvalidArgumentException('You provided an invalid URL: ' . $url);
        }

        $this->url = $filtered_url;
    }

    /**
     * Setting the timeout will define how long Diffbot will keep trying
     * to fetch the API results. A timeout can happen for various reasons, from
     * Diffbot's failure, to the site being crawled being exceptionally slow, and more.
     *
     * @param int|null $timeout Defaults to 30000 even if not set
     *
     * @return $this
     */
    public function setTimeout($timeout = null)
    {
        if ($timeout === null) {
            $timeout = 30000;
        }
        if (!is_int($timeout)) {
            throw new \InvalidArgumentException('Parameter is not an integer');
        }
        if ($timeout < 0) {
            throw new \InvalidArgumentException('Parameter is negative. Only positive timeouts accepted.');
        }

        $this->timeout = $timeout;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $prefix = substr(lcfirst($name), 0, 3);
        $field = lcfirst(substr($name, 3));

        $fields = static::getOptionalFields();
        if (in_array($field, $fields)) {
            if ($prefix == 'get') {
                return (isset($this->fieldSettings[$field])) ? $this->fieldSettings[$field] : false;
            }

            if ($prefix == 'set') {
                if (is_bool($arguments[0]) || $arguments[0] === null) {
                    $this->fieldSettings[$field] = (bool)$arguments[0];
                    return $this;
                }
                throw new \InvalidArgumentException('Only booleans and null are allowed as optional field flags!');
            }
            throw new \BadMethodCallException('Prefix "'.$prefix.'" not allowed.');
        }
        throw new \BadMethodCallException($name . ': such a field does not exist for this API class.');
    }
}
