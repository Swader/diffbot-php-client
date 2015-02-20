<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;
use Swader\Diffbot\Traits\StandardEntity;

class Image extends Entity
{
    use StandardEntity;

    /**
     * Should always return "image"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * An internal identifier for Diffbot, used for indexing in their databases
     * @return string
     */
    public function getDiffbotUri()
    {
        return $this->data['diffbotUri'];
    }

    /**
     * Height of image as (re-)sized via browser/CSS.
     * @return mixed
     */
    public function getHeight()
    {
        return (isset($this->data['height'])) ? $this->data['height'] : $this->getNaturalHeight();
    }

    /**
     * Width of image as (re-)sized via browser/CSS.
     * @return mixed
     */
    public function getWidth()
    {
        return (isset($this->data['width'])) ? $this->data['width'] : $this->getNaturalWidth();
    }

    /**
     * Raw image height, in pixels.
     * @return mixed
     */
    public function getNaturalHeight()
    {
        return $this->data['naturalHeight'];
    }

    /**
     * Raw image width, in pixels.
     * @return mixed
     */
    public function getNaturalWidth()
    {
        return $this->data['naturalWidth'];
    }

}