<?php

namespace Swader\Diffbot\Api;

use Swader\Diffbot\Abstracts\Api;
use Swader\Diffbot\Entity\SearchInfo;
use Swader\Diffbot\Traits\DiffbotAware;
use Swader\Diffbot\Entity\EntityIterator;

/**
 * Class Search
 * @see https://www.diffbot.com/dev/docs/search/
 * @package Swader\Diffbot\Api
 */
class Search extends Api
{
    use DiffbotAware;

    /** @var string API URL to which to send the request */
    protected $apiUrl = 'https://api.diffbot.com/v3/search';

    /** @var string */
    protected $col = null;

    /** @var string Search query to execute */
    protected $query = '';

    /** @var SearchInfo */
    protected $info;

    const SEARCH_ALL = 'all';

    /**
     * Search query.
     * @see https://www.diffbot.com/dev/docs/search/#query
     * @param string string $q
     */
    public function __construct($q)
    {
        $this->query = $q;
    }

    /**
     * Name of the collection (Crawlbot or Bulk API job name) to search.
     * By default the search will operate on all of your token's collections.
     *
     * @param null|string $col
     * @return $this
     */
    public function setCol($col = null)
    {
        if ($col !== null) {
            $this->otherOptions['col'] = $col;
        } else {
            unset($this->otherOptions['col']);
        }

        return $this;
    }

    /**
     * Number of results to return. Default is 20. To return all results in
     * the search, pass num=all.
     * @param int $num
     * @return $this
     */
    public function setNum($num = 20)
    {
        if (!is_numeric($num) && $num !== self::SEARCH_ALL) {
            throw new \InvalidArgumentException(
                'Argument can only be numeric or "all" to return all results.'
            );
        }
        $this->otherOptions['num'] = $num;

        return $this;
    }

    /**
     * Ordinal position of first result to return. (First position is 0.)
     * Default is 0.
     * @param int $start
     * @return $this
     */
    public function setStart($start = 0)
    {
        if (!is_numeric($start)) {
            throw new \InvalidArgumentException(
                'Argument can only be numeric.'
            );
        }
        $this->otherOptions['start'] = $start;

        return $this;
    }

    /**
     * Builds out the URL string that gets requested once `call()` is called
     *
     * @return string
     */
    public function buildUrl()
    {

        $url = rtrim($this->apiUrl, '/') . '?';

        // Add token
        $url .= 'token=' . $this->diffbot->getToken();

        // Add query
        $url .= '&query=' . urlencode($this->query);

        // Add other options
        foreach ($this->otherOptions as $option => $value) {
            $url .= '&' . $option . '=' . $value;
        }

        return $url;
    }

    /**
     * If you pass in `true`, you get back a SearchInfo object related to the
     * last call. Keep in mind that passing in true before calling a default
     * call() will implicitly call the call(), and then get the SearchInfo.
     *
     * So:
     *
     * $searchApi->call() // gets entities
     * $searchApi->call(true) // gets SearchInfo about the executed query
     *
     * @todo: remove error avoidance when issue 12 is fixed: https://github.com/Swader/diffbot-php-client/issues/12
     * @param bool $info
     * @return EntityIterator|SearchInfo
     */
    public function call($info = false)
    {
        if (!$info) {
            $ei = parent::call();

            set_error_handler(function() { /* ignore errors */ });
            $arr = json_decode((string)$ei->getResponse()->getBody(), true, 512, 1);
            restore_error_handler();

            unset($arr['request']);
            unset($arr['objects']);

            $this->info = new SearchInfo($arr);

            return $ei;
        }

        if ($info && !$this->info) {
            $this->call();
        }

        return $this->info;
    }
}