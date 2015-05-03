<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;
use Swader\Diffbot\Traits\StandardEntity;

class Discussion extends Entity
{
    use StandardEntity;

    protected $posts = [];

    public function __construct(array $data)
    {
        parent::__construct($data);
        foreach ($this->data['posts'] as $post) {
            $this->posts[] = new Post($post);
        }
        $this->data['posts'] = $this->posts;
    }

    /**
     * Should always return "discussion"
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * Number of individual posts in the thread
     * @return int
     */
    public function getNumPosts()
    {
        return (int)$this->data['numPosts'];
    }

    /**
     * Array containing all tags that Diffbot's AI concluded match the content.
     *
     * Note that these are *not* the meta tags as defined by the creator of the
     * content, but machine learned ones. The format of the array is:
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
     * Number of unique participants in the discussion thread or comments
     *
     * @return int
     */
    public function getParticipants()
    {
        return (int)$this->data['participants'];
    }

    /**
     * Number of pages in the thread concatenated to form the posts response.
     * Use maxPages to define how many pages to concatenate.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return int
     */
    public function getNumPages()
    {
        return (isset($this->data['numPages'])) ? $this->data['numPages'] : 1;
    }

    /**
     * Array of all page URLs concatenated in a multipage discussion.
     * Empty array if article was not concatenated before being returned.
     * @see http://support.diffbot.com/automatic-apis/handling-multiple-page-articles/
     * @return array
     */
    public function getNextPages()
    {
        return (isset($this->data['nextPages'])) ? $this->data['nextPages'] : [];
    }

    /**
     * If discussion spans multiple pages, nextPage will return the subsequent
     * page URL.
     * @return string|null
     */
    public function getNextPage()
    {
        return (isset($this->data['nextPage'])) ? $this->data['nextPage'] : null;
    }

    /**
     * Discussion service provider (e.g., Disqus, Facebook), if known.
     * @return string|null
     */
    public function getProvider()
    {
        return (isset($this->data['provider'])) ? $this->data['provider'] : null;
    }

    /**
     * URL of the discussion's RSS feed, if available.
     * @return string|null
     */
    public function getRssUrl()
    {
        return (isset($this->data['rssUrl'])) ? $this->data['rssUrl'] : null;
    }

    /**
     * @todo find out what this is
     * @return float|null
     */
    public function getConfidence()
    {
        return (isset($this->data['confidence'])) ? (float)$this->data['confidence'] : null;
    }

    /**
     * Returns an array of posts or comments in discussion.
     * Each post is a Post entity.
     *
     * @see Post
     * @return array
     */
    public function getPosts()
    {
        return $this->posts;
    }

}