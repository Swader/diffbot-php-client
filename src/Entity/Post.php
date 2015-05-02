<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;

class Post extends Entity
{

    /**
     * Should always return "post"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
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
     * Returns the human language of the page as determined by Diffbot when looking at content.
     * The code returned is a two-character ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     * @return string
     */
    public function getLang()
    {
        return $this->data['humanLanguage'];
    }

    /**
     * Returns plaintext version of article (no HTML) as parsed by Diffbot.
     * Only the content is returned, the text in the surrounding (layout etc) elements is ignored.
     * @return string
     */
    public function getText()
    {
        return $this->data['text'];
    }

    /**
     * Returns full HTML of the article's content - only the content, not the surrounding layout HTML.
     * @return string
     */
    public function getHtml()
    {
        return $this->data['html'];
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
        return $this->data['date'];
    }

    /**
     * Returns the full name of the author, as signed on the article's page
     * @return string | null
     */
    public function getAuthor()
    {
        return (isset($this->data['author'])) ? $this->data['author'] : null;
    }

    /**
     * Returns the url of the author - their profile URL if available
     * @return string | null
     */
    public function getAuthorUrl()
    {
        return (isset($this->data['authorUrl'])) ? $this->data['authorUrl'] : null;
    }

    /**
     * The array returned will contain all tags that Diffbot's AI concluded match the content
     *
     * Note that these are *not* the meta tags as defined by the author, but machine learned ones.
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
        return (isset($this->data['tags'])) ? $this->data['tags'] : [];
    }

    /**
     * Returns the sentiment score of the analyzed article text, a value randing from
     * -1.0 (very negative) to 1.0 (very positive).
     * @return float|null
     */
    public function getSentiment()
    {
        return (isset($this->data['sentiment'])) ? $this->data['sentiment'] : null;
    }

    /**
     * Returns the number of upvotes on a post or 0 if none or unavailable
     * @return int
     */
    public function getVotes()
    {
        return (isset($this->data['votes'])) ? $this->data['votes'] : null;
    }

    /**
     * Returns the ID of the post (usually the ordinary number of the post in
     * the list of all posts, starting with 0 for the first one
     * @return int
     */
    public function getId()
    {
        return (isset($this->data['id'])) ? $this->data['id'] : 1;
    }

    /**
     * If the post is a reply, this is the ID of the post it replies to
     * @return int
     */
    public function getParentId()
    {
        return (isset($this->data['parentId'])) ? $this->data['parentId'] : null;
    }

    /**
     * Returns an array of images found in the page's content.
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
        return (isset($this->data['images'])) ? $this->data['images'] : [];
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
     * An internal identifier for Diffbot, used for indexing in their databases
     * @return string
     */
    public function getDiffbotUri()
    {
        return $this->data['diffbotUri'];
    }
}