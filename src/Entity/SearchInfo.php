<?php

namespace Swader\Diffbot\Entity;

use Swader\Diffbot\Abstracts\Entity;

class SearchInfo extends Entity
{

    /**
     * Should always return "searchInfo"
     * @return string
     */
    public function getType()
    {
        return $this->data['searchInfo'];
    }

    /**
     * Current UTC time as timestamp
     * @return int
     */
    public function getCurrentTimeUTC()
    {
        return (int)$this->data['currentTimeUTC'];
    }

    /**
     * Response time in milliseconds. Time it took to process the query on
     * Diffbot's end.
     * @return int
     */
    public function getResponseTimeMS()
    {
        return (int)$this->data['responseTimeMS'];
    }

    /**
     * Number of results skipped for any reason
     * @todo: find out why results might be omitted
     * @return int
     */
    public function getNumResultsOmitted()
    {
        return (int)$this->data['numResultsOmitted'];
    }

    /**
     * Number of skipped shards
     * @todo: find out what shards are
     * @return int
     */
    public function getNumShardsSkipped()
    {
        return (int)$this->data['numShardsSkipped'];
    }

    /**
     * Total number of shards
     * @todo: find out what shards are
     * @return int
     */
    public function getTotalShards()
    {
        return (int)$this->data['totalShards'];
    }

    /**
     * Total number of documents in collection.
     * Should resemble the total number you got on the crawl job.
     * @todo: find out why not identical
     * @return int
     */
    public function getDocsInCollection()
    {
        return (int)$this->data['docsInCollection'];
    }

    /**
     * Number of results that match - NOT the number of *returned* results!
     * @return int
     */
    public function getHits()
    {
        return (int)$this->data['hits'];
    }

    /**
     * Returns an assoc. array containing the following keys and example values:
     *

    "fullQuery" => "type:json AND (author:\"Miles Johnson\" AND type:article)",
    "queryLanguageAbbr" => "xx",
    "queryLanguage" => "Unknown",
    "terms" => [
        [
        "termNum" => 0,
        "termStr" => "Miles Johnson",
        "termFreq" => 2621376,
        "termHash48" => 224575481707228,
        "termHash64" => 4150001371756911641,
        "prefixHash64" => 3732660069076179349
        ],
        [
        "termNum" => 1,
        "termStr" => "type:json",
        "termFreq" => 2621664,
        "termHash48" => 272064464231140,
        "termHash64" => 9877301297136722857,
        "prefixHash64" => 7586288672657224048
        ],
        [
        "termNum" => 2,
        "termStr" => "type:article",
        "termFreq" => 524448,
        "termHash48" => 210861560163398,
        "termHash64" => 12449358332005671483,
        "prefixHash64" => 7586288672657224048
        ]
    ]

     * @todo: find out what hashes are, and to what the freq is relative
     * @return array
     */
    public function getQueryInfo()
    {
        return (array)$this->data['queryInfo'];
    }

}