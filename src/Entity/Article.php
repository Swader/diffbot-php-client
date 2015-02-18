<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;

class Article extends Entity
{
    /**
     * Should always return "article"
     * @return string
     */
    public function getType()
    {
        return $this->objects['type'];
    }

    /**
     * Returns the URL which was crawled
     * @return string
     */
    public function getPageUrl()
    {
        return $this->objects['pageUrl'];
    }

    /**
     * Returns page Url which was resolved by redirects, if any.
     * For example, crawling a bitly link will make this method return the ultimate destination's URL
     * @return string
     */
    public function getResolvedPageUrl()
    {
        return (isset($this->objects['resolvedPageUrl'])) ? $this->objects['resolvedPageUrl'] : $this->getPageUrl();
    }

    /**
     * Returns title of article as deducted by Diffbot
     * @return string
     */
    public function getTitle()
    {
        return $this->objects['title'];
    }

    /**
     * Returns plaintext version of article (no HTML) as parsed by Diffbot.
     * Only the content is returned, the text in the surrounding (layout etc) elements is ignored.
     * @return string
     */
    public function getText()
    {
        return $this->objects['text'];
    }

    /**
     * Returns full HTML of the article's content - only the content, not the surrounding layout HTML.
     * @return string
     */
    public function getHtml()
    {
        return $this->objects['html'];
    }

    /**
     * Returns date as per http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3
     * Example date: "Wed, 18 Dec 2013 00:00:00 GMT"
     * Note that this is "strtotime" friendly for further conversions
     * @todo add more formats as method arguments
     * @return string
     */
    public function getDate()
    {
        return $this->objects['date'];
    }

    /**
     * Returns the full name of the author, as signed on the article's page
     * @return string
     */
    public function getAuthor()
    {
        return $this->objects['author'];
    }

    /**
     * The array returned will contain all tags that Diffbot's AI concluded match the content
     *
     * Note that these are *not* the meta tags as defined by the author, but machine learned ones.
     * Note also that tags may differ depending on URL. Visiting a bitly link vs visiting a fully resolved one
     * will sometimes yield different results. It is currently unknown why this happens.
     * The format of the array is:
     *
     * [
     *  [
     *      "id": 133907,
     *      "count": 3,
     *      "prevalence": 0.3103448275862069,
     *      "label": "Apache HTTP Server",
     *      "type": "Http://www.ontologydesignpatterns.org/ont/dul/DUL.owl#InformationEntity",
     *      "uri": "http://dbpedia.org/resource/Apache_HTTP_Server"
     *  ],
     *  [
     *      "id": 208652,
     *      "count": 5,
     *      "prevalence": 0.5172413793103449,
     *      "label": "PHP",
     *      "type": "Http://www.ontologydesignpatterns.org/ont/dul/DUL.owl#InformationEntity",
     *      "uri": "http://dbpedia.org/resource/PHP"
     *  ]
     * ]
     *
     * @return array
     */
    public function getTags()
    {
        return $this->objects['tags'];
    }

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
     * Returns the human language as determined by Diffbot when looking at content.
     * The code returned is a two-character ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     * @return string
     */
    public function getLang()
    {
        return $this->objects['humanLanguage'];
    }

    /**
     * Number of pages automatically concatenated to form the text or html response.
     * By default, Diffbot will automatically concatenate up to 20 pages of an article.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return int
     */
    public function getNumPages()
    {
        return (isset($this->objects['numPages'])) ? $this->objects['numPages'] : 1;
    }

    /**
     * Array of all page URLs concatenated in a multipage article.
     * Empty array if article was not concatenated before being returned.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return array
     */
    public function getNextPages()
    {
        return (isset($this->objects['nextPages'])) ? $this->objects['nextPages'] : [];
    }

    /**
     * Returns an array of images found in the article's content.
     *
     * Note that this (tries) to ignore content-unrelated images like ads arounds the page, etc.
     * The format of the array will be:
     *
     * [
     *  {
     *      "height": 808,
     *      "diffbotUri": "image|3|-543943368",
     *      "naturalHeight": 808,
     *      "width": 717,
     *      "primary": true,
     *      "naturalWidth": 717,
     *      "url": "https://example.com/image1.png"
     *  },
     *  {
     *      "height": 506,
     *      "diffbotUri": "image|3|-844014913",
     *      "naturalHeight": 506,
     *      "width": 715,
     *      "naturalWidth": 715,
     *      "url": "https://example.com/image1.jpeg"
     *  }
     * ]
     *
     * @return array
     */
    public function getImages()
    {
        return (isset($this->objects['images'])) ? $this->objects['images'] : [];
    }

    /**
     * Returns an array of videos found in the article's content.
     *
     * The format of the array will be:
     *
     * [
     *  {
     *      "diffbotUri": "video|3|-1138675744",
     *      "primary": true,
     *      "url": "http://player.vimeo.com/video/22439234"
     *  },
     *  {
     *      "diffbotUri": "video|3|-1138675744",
     *      "primary": true,
     *      "url": "http://player.vimeo.com/video/22439234"
     *  }
     * ]
     *
     * @return array
     */
    public function getVideos() {
        return (isset($this->objects['images'])) ? $this->objects['images'] : [];
    }

    /**
     * An internal identifier for Diffbot, used for indexing in their databases
     * @return string
     */
    public function getDiffbotUri()
    {
        return $this->objects['diffbotUri'];
    }

    public function getLinks() {

    }

    public function getMeta() {

    }

    public function getQueryString() {

    }
}