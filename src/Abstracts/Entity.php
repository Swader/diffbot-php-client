<?php

namespace Swader\Diffbot\Abstracts;

use GuzzleHttp\Message\Response;

abstract class Entity
{
    /** @var Response */
    protected $response;

    /** @var  array */
    protected $objects;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->objects = $response->json()['objects'][0];
    }

    /**
     * Returns the original response that was passed into the Entity
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}