<?php

namespace Swader\Diffbot\Interfaces;

use GuzzleHttp\Message\Response;
use Swader\Diffbot\Abstracts\Entity;

interface EntityFactory
{
    /**
     * Returns the entity iterator containing the appropriate entities as built by the contents of $response
     *
     * @param Response $response
     * @return Entity
     */
    public function createAppropriateIterator(Response $response);
}