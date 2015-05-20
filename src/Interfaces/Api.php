<?php

namespace Swader\Diffbot\Interfaces;
use Swader\Diffbot\Entity\EntityIterator;

interface Api
{
    /**
     * @param int|null $timeout
     * @return $this
     */
    public function setTimeout($timeout = null);

    /**
     * @return EntityIterator
     */
    public function call();

    /**
     * @return string
     */
    public function buildUrl();
}
