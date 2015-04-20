<?php

namespace Swader\Diffbot\Interfaces;

use GuzzleHttp\Message\ResponseInterface as Response;
use Swader\Diffbot\Entity\EntityIterator;

interface EntityFactory
{
    /**
     * Returns the entity iterator containing the appropriate entities as built by the contents of $response
     *
     * @param Response $response
     * @return EntityIterator
     */
    public function createAppropriateIterator(Response $response);
}