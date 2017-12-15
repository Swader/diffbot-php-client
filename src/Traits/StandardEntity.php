<?php

namespace Swader\Diffbot\Traits;

/**
 * Trait StandardEntity
 * @package Swader\Diffbot\Traits
 * @property $data array
 */
trait StandardEntity {

    /**
     * Alias for getLang()
     * @see getLang()
     * @return string
     */
    public function getHumanLanguage()
    {
        return $this->getLang();
    }

    /**
     * Returns the human language of the page as determined by Diffbot when looking at content.
     * The code returned is a two-character ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     * @return string
     */
    public function getLang()
    {
        return $this->data['humanLanguage'];
    }

    /**
     * Returns the URL which was crawled
     * @return string
     */
    public function getPageUrl()
    {
        return $this->data['pageUrl'];
    }

    /**
     * Returns page Url which was resolved by redirects, if any.
     * For example, crawling a bitly link will make this method return the ultimate destination's URL
     * @return string
     */
    public function getResolvedPageUrl()
    {
        return (isset($this->data['resolvedPageUrl'])) ? $this->data['resolvedPageUrl'] : $this->getPageUrl();
    }

    /**
     * Returns title of article as deduced by Diffbot
     * @return string
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * Returns an array of all links found on the crawled page.
     *
     * Only available in the standard APIs, not Custom APIs
     * @return array|null
     */
    public function getLinks()
    {
        return (isset($this->data['links'])) ? $this->data['links'] : null;
    }

    /**
     * Returns a top-level object (meta) containing the full contents of page meta tags,
     * including sub-arrays for OpenGraph tags, Twitter Card metadata, schema.org microdata,
     * and -- if available -- oEmbed metadata.
     *
     * Only available in the standard APIs, not Custom APIs
     * @return array|null
     */
    public function getMeta()
    {
        return (isset($this->data['meta'])) ? $this->data['meta'] : null;
    }

    /**
     * Returns any key/value pairs present in the URL querystring.
     * Items without a discrete value will be returned as true.
     *
     * Only available in the standard APIs, not Custom APIs
     * @return array|null
     */
    public function getQueryString()
    {
        return (isset($this->data['queryString'])) ? $this->data['queryString'] : null;
    }

    /**
     * Returns a top-level array (breadcrumb) of URLs and link text from page breadcrumbs.
     *
     * Only available in the standard APIs, not Custom APIs
     * @return array|null
     */
    public function getBreadcrumb()
    {
        return (isset($this->data['breadcrumb'])) ? $this->data['breadcrumb'] : null;
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
     * Returns the timestamp from the point in time the page was indexed by the engine
     * Example date: "Wed, 18 Dec 2013 00:00:00 GMT"
     * This will be a Carbon (https://github.com/briannesbitt/Carbon) instance if Carbon is installed.
     * @return \Carbon\Carbon | string
     */
    public function getTimestamp()
    {
        return (class_exists('\Carbon\Carbon')) ?
            new \Carbon\Carbon($this->data['timestamp'], 'GMT') :
            $this->data['timestamp'];
    }

    protected function getOrDefault($key, $default = null, $data = null)
    {
        $data = ($data !== null) ?: $this->data;
        return (isset($data[$key]) ? $data[$key] : $default);
    }

}
