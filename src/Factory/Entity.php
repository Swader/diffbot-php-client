<?php

namespace Swader\Diffbot\Factory;

use GuzzleHttp\Message\Response;
use Swader\Diffbot\Exceptions\DiffbotException;
use Swader\Diffbot\Interfaces\EntityFactory;

class Entity implements EntityFactory
{
    protected $apiEntities = [
        'product' => '\Swader\Diffbot\Entity\Product',
        'article' => '\Swader\Diffbot\Entity\Article',
        'image' => '\Swader\Diffbot\Entity\Image',
        'analyze' => '\Swader\Diffbot\Entity\Analyze',
        '*' => '\Swader\Diffbot\Entity\Wildcard',
    ];

    /**
     * Creates an appropriate Entity from a given Response
     * If no valid Entity can be found for typoe of API, the Wildcard entity is selected
     *
     * @param Response $response
     * @return \Swader\Diffbot\Abstracts\Entity
     * @throws DiffbotException
     */
    public function createAppropriate(Response $response)
    {
        $this->checkResponseFormat($response);

        $arr = $response->json();
        if (isset($this->apiEntities[$arr['request']['api']])) {
            $class = $this->apiEntities[$arr['request']['api']];
        } else {
            $class = $this->apiEntities['*'];
        }
        return new $class($response);
    }

    /**
     * Makes sure the Diffbot response has all the fields it needs to work properly
     *
     * @param Response $response
     * @throws DiffbotException
     */
    protected function checkResponseFormat(Response $response)
    {
        $arr = $response->json();

        if (!isset($arr['objects'])) {
            throw new DiffbotException('Objects property missing - cannot extract entity values');
        }

        if (!isset($arr['request'])) {
            throw new DiffbotException('Request property not found in response!');
        }

        if (!isset($arr['request']['api'])) {
            throw new DiffbotException('API property not found in request property of response!');
        }
    }
}