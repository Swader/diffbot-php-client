<?php

namespace Swader\Diffbot\Interfaces;

use GuzzleHttp\Message\Response;

interface EntityFactory
{
    /**
     * Returns the appropriate entity as built by the contents of $response
     *
     * @param Response $response
     * @return Entity
     */
    public function createAppropriate(Response $response);
}