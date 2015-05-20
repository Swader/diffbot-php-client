<?php

namespace Swader\Diffbot\Traits;

use Swader\Diffbot\Diffbot;

/**
 * Class DiffbotAware
 * @property Diffbot diffbot
 * @package Swader\Diffbot\Traits
 */
trait DiffbotAware
{
    /**
     * Sets the Diffbot instance on the child class
     * Used to later fetch the token, HTTP client, EntityFactory, etc
     * @param Diffbot $d
     * @return $this
     */
    public function registerDiffbot(Diffbot $d)
    {
        $this->diffbot = $d;

        return $this;
    }
}