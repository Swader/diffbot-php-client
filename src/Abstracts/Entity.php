<?php

namespace Swader\Diffbot\Abstracts;

abstract class Entity
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the original response that was passed into the Entity
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}