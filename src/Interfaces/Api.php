<?php

namespace Swader\Diffbot\Interfaces;

interface Api
{
    public function setTimeout($timeout = null);

    public function call();
}
