<?php

namespace Swader\Diffbot;

use Swader\Diffbot\Api\Crawl;
use Swader\Diffbot\Api\Custom;
use Swader\Diffbot\Exceptions\DiffbotException;
use Swader\Diffbot\Api\Product;
use Swader\Diffbot\Api\Image;
use Swader\Diffbot\Api\Analyze;
use Swader\Diffbot\Api\Article;
use Swader\Diffbot\Api\Discussion;
use GuzzleHttp\Client;
use Swader\Diffbot\Factory\Entity;
use Swader\Diffbot\Interfaces\Api;
use Swader\Diffbot\Interfaces\EntityFactory;

/**
 * Class Diffbot
 *
 * The main class for API consumption
 *
 * @package Swader\Diffbot
 */
class Diffbot
{
    /** @var string The API access token */
    protected static $token = null;

    /** @var string The instance token, settable once per new instance */
    protected $instanceToken;

    /** @var Client The HTTP clients to perform requests with */
    protected $client;

    /** @var  EntityFactory The Factory which created Entities from Responses */
    protected $factory;

    /**
     * @param string|null $token The API access token, as obtained on diffbot.com/dev
     * @throws DiffbotException When no token is provided
     */
    public function __construct($token = null)
    {
        if ($token === null) {
            if (self::$token === null) {
                $msg = 'No token provided, and none is globally set. ';
                $msg .= 'Use Diffbot::setToken, or instantiate the Diffbot class with a $token parameter.';
                throw new DiffbotException($msg);
            }
        } else {
            self::validateToken($token);
            $this->instanceToken = $token;
        }
    }

    /**
     * Sets the token for all future new instances
     * @param string $token The API access token, as obtained on diffbot.com/dev
     * @return void
     */
    public static function setToken($token)
    {
        self::validateToken($token);
        self::$token = $token;
    }

    private static function validateToken($token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('Token is not a string.');
        }
        if (strlen($token) < 4) {
            throw new \InvalidArgumentException('Token "' . $token . '" is too short, and thus invalid.');
        }
        return true;
    }

    /**
     * Returns the token that has been defined.
     * @return null|string
     */
    public function getToken()
    {
        return ($this->instanceToken) ? $this->instanceToken : self::$token;
    }

    /**
     * Sets the client to be used for querying the API endpoints
     *
     * @param Client $client
     * @return $this
     */
    public function setHttpClient(Client $client = null)
    {
        if ($client === null) {
            $client = new Client();
        }
        $this->client = $client;
        return $this;
    }

    /**
     * Returns either the instance of the Guzzle client that has been defined, or null
     * @return Client|null
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Sets the Entity Factory which will create the Entities from Responses
     * @param EntityFactory $factory
     * @return $this
     */
    public function setEntityFactory(EntityFactory $factory = null)
    {
        if ($factory === null) {
            $factory = new Entity();
        }
        $this->factory = $factory;
        return $this;
    }

    /**
     * Returns the Factory responsible for creating Entities from Responses
     * @return EntityFactory
     */
    public function getEntityFactory()
    {
        return $this->factory;
    }


    /**
     * Creates a Product API interface
     *
     * @param string $url Url to analyze
     * @return Product
     */
    public function createProductAPI($url)
    {
        $api = new Product($url);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates an Article API interface
     *
     * @param string $url Url to analyze
     * @return Article
     */
    public function createArticleAPI($url)
    {
        $api = new Article($url);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates an Image API interface
     *
     * @param string $url Url to analyze
     * @return Image
     */
    public function createImageAPI($url)
    {
        $api = new Image($url);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates an Analyze API interface
     *
     * @param string $url Url to analyze
     * @return Analyze
     */
    public function createAnalyzeAPI($url)
    {
        $api = new Analyze($url);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates an Discussion API interface
     *
     * @param string $url Url to analyze
     * @return Discussion
     */
    public function createDiscussionAPI($url)
    {
        $api = new Discussion($url);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates a generic Custom API
     *
     * Does not have predefined Entity, so by default returns Wildcards
     *
     * @param string $url Url to analyze
     * @param string $name Name of the custom API, required to finalize URL
     * @return Custom
     */
    public function createCustomAPI($url, $name)
    {
        $api = new Custom($url, $name);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

    /**
     * Creates a new Crawljob with the given name.
     *
     * @see https://www.diffbot.com/dev/docs/crawl/
     *
     * @param string $name Name of the crawljob. Needs to be unique.
     * @param Api $api Optional instance of an API - if omitted, must be set
     * later manually
     * @return Crawl
     */
    public function crawl($name = null, Api $api = null)
    {
        $api = new Crawl($name, $api);
        if (!$this->getHttpClient()) {
            $this->setHttpClient();
            $this->setEntityFactory();
        }
        return $api->registerDiffbot($this);
    }

}