<?php

namespace Swader\Diffbot\Entity;

use GuzzleHttp\Message\ResponseInterface as Response;
use Swader\Diffbot\Abstracts\Entity;

class EntityIterator implements \Countable, \Iterator, \ArrayAccess
{
    /** @var  array */
    protected $data;
    /** @var  int */
    protected $cursor = -1;
    /** @var  Entity */
    protected $current;
    /** @var  Response */
    protected $response;

    public function __construct(array $objects, Response $response)
    {
        $this->response = $response;
        $this->data = $objects;
        $this->next();
    }

    /**
     * Returns the original response
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function count()
    {
        return count($this->data);
    }

    public function rewind()
    {
        if ($this->cursor > 0) {
            $this->cursor = -1;
            $this->next();
        }
    }

    public function key()
    {
        return $this->cursor;
    }

    public function current()
    {
        while(!$this->offsetExists($this->cursor)) {
            $this->next();
        }
        return $this->data[$this->cursor];
    }

    public function next()
    {
        $this->cursor++;
    }

    public function valid()
    {
        return ($this->cursor <= max(array_keys($this->data)));
    }

    protected function _getZerothEntity()
    {
        return ($this->cursor == -1) ? $this->data[0] : $this->current();
    }

    public function __call($name, $args)
    {
        $isGetter = substr($name, 0, 3) == 'get';

        if ($isGetter) {
            $zeroth = $this->_getZerothEntity();
            if (method_exists($this->_getZerothEntity(), $name)) {
                $rv = $zeroth->$name(...$args);
            } else {
                $property = lcfirst(substr($name, 3, strlen($name) - 3));
                $rv = $zeroth->$property;
            }

            return $rv;
        }

        throw new \BadMethodCallException('No such method: ' . $name);
    }

    public function __get($name)
    {
        $entity = $this->_getZerothEntity();

        return $entity->$name;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return (isset($this->data[$offset]));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(
            'Resultset is read only'
        );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}