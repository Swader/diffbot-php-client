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
     * Height of image as (re-)sized via browser/CSS.
     * @return mixed
     */
    public function getHeight()
    {
        return (isset($this->data['height']))
            ? $this->data['height'] : $this->getNaturalHeight();
    }

    /**
     * Width of image as (re-)sized via browser/CSS.
     * @return mixed
     */
    public function getWidth()
    {
        return (isset($this->data['width']))
            ? $this->data['width'] : $this->getNaturalWidth();
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

    /**
     * Returns the URL of the image
     * @return string
     */
    public function getUrl()
    {
        return $this->data['url'];
    }

    /**
     * Returns the URL to which the image is linked, if any. Otherwise, null.
     * @return string|null
     */
    public function getAnchorUrl()
    {
        return (isset($this->data['anchorUrl']))
            ? $this->data['anchorUrl'] : null;
    }

    /**
     * XPath Expression identifying the image node in the page
     * @return string
     */
    public function getXPath()
    {
        return $this->data['xpath'];
    }

    /**
     * Returns an array of [title, link] arrays for all posts where this image,
     * or a similar one, was found
     * @return array
     */
    public function getMentions()
    {
        $d = $this->data;

        return (isset($d['mentions']) && !empty($d['mentions']))
            ? $d['mentions'] : [];
    }

    /**
     * Returns recognized faces
     * Currently doesn't even recognize Oprah, heavy beta, do not use
     * @return string
     */
    public function getFaces()
    {
        $d = $this->data;

        return (isset($d['faces']) && !empty($d['faces']))
            ? $d['faces'] : "";
    }

    /**
     * Returns recognized text from picture
     * Currently does not recognize anything, heavy beta, do not use
     * @return string
     */
    public function getOcr()
    {
        return (isset($this->data['ocr']) && !empty($this->data['ocr']))
            ? $this->data['ocr'] : "";
    }
}