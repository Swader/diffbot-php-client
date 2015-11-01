<?php

namespace Swader\Diffbot\Factory;

use Psr\Http\Message\ResponseInterface as Response;
use Swader\Diffbot\Entity\EntityIterator;
use Swader\Diffbot\Exceptions\DiffbotException;
use Swader\Diffbot\Interfaces\EntityFactory;

class Entity implements EntityFactory
{
    protected $apiEntities = [
        'product' => '\Swader\Diffbot\Entity\Product',
        'article' => '\Swader\Diffbot\Entity\Article',
        'image' => '\Swader\Diffbot\Entity\Image',
        'discussion' => '\Swader\Diffbot\Entity\Discussion',
        '*' => '\Swader\Diffbot\Entity\Wildcard',
    ];

    /**
     * Creates an appropriate Entity from a given Response
     * If no valid Entity can be found for typo of API, the Wildcard entity is selected
     *
     * @todo: remove error avoidance when issue 12 is fixed: https://github.com/Swader/diffbot-php-client/issues/12
     * @param Response $response
     * @return EntityIterator
     * @throws DiffbotException
     */
    public function createAppropriateIterator(Response $response)
    {
        $this->checkResponseFormat($response);

        set_error_handler(function() { /* ignore errors */ });
        $arr = json_decode((string)$response->getBody(), true, 512, 1);
        restore_error_handler();

        $objects = [];
        foreach ($arr['objects'] as $object) {
            if (isset($this->apiEntities[$object['type']])) {
                $class = $this->apiEntities[$object['type']];
            } else {
                $class = $this->apiEntities['*'];
            }
            $objects[] = new $class($object);
        }

        return new EntityIterator($objects, $response);
    }

    /**
     * Makes sure the Diffbot response has all the fields it needs to work properly
     *
     * @todo: remove error avoidance when issue 12 is fixed: https://github.com/Swader/diffbot-php-client/issues/12
     * @param Response $response
     * @throws DiffbotException
     */
    protected function checkResponseFormat(Response $response)
    {
        set_error_handler(function() { /* ignore errors */ });
        $arr = json_decode((string)$response->getBody(), true, 512, 1);
        restore_error_handler();

        if (isset($arr['error'])) {
            throw new DiffbotException('Diffbot returned error ' . $arr['errorCode'] . ': ' . $arr['error']);
        }

        $required = [
            'objects' => 'Objects property missing - cannot extract entity values',
            'request' => 'Request property not found in response!'
        ];

        foreach ($required as $k=>$v) {
            if (!isset($arr[$k])) {
                throw new DiffbotException($v);
            }
        }

    }
}