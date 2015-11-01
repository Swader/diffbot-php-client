<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;
use Swader\Diffbot\Traits\StandardEntity;

class Article extends Entity
{
    use StandardEntity;

    /** @var Discussion */
    protected $discussion = null;

    public function __construct(array $data)
    {
        parent::__construct($data);
        if (isset($this->data['discussion'])) {
            $this->discussion = new Discussion($this->data['discussion']);
        }
    }

    /**
     * Should always return "article"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
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
     * Returns the URL of the author's profile, if available. Otherwise, null.
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
        return $this->data['tags'];
    }

    /**
     * Number of pages automatically concatenated to form the text or html response.
     * By default, Diffbot will automatically concatenate up to 20 pages of an article.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return int
     */
    public function getNumPages()
    {
        return (isset($this->data['numPages'])) ? $this->data['numPages'] : 1;
    }

    /**
     * Array of all page URLs concatenated in a multipage article. Max 20 entries.
     * Empty array if article was not concatenated before being returned.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return array
     */
    public function getNextPages()
    {
        return (isset($this->data['nextPages'])) ? $this->data['nextPages'] : [];
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
     * Returns an array of videos found in the article's content.
     *
     * Works on and off - the better choice is the Video API
     * @see https://www.diffbot.com/dev/docs/video
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
    public function getVideos()
    {
        return (isset($this->data['videos'])) ? $this->data['videos'] : [];
    }

    /**
     * Returns the Discussion entity - comments of the article
     * @return Discussion
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }
}