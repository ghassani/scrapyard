<?php

namespace Spliced\Lodestone\Response;

use Spliced\Lodestone\Model\CharacterSearchResult;

/**
 * Class FreeCompanySearchResults
 *
 * @package Spliced\Lodestone
 */
class FreeCompanySearchResults
{

    protected $results = array();

    protected $page = 1;

    protected $totalResults = 0;

    public function __construct(array $results = array(), $page = 1, $totalResults = 0)
    {
        foreach ($results as $result) {
            if (!$result instanceof CharacterSearchResult) {
                throw new \InvalidArgumentException('CharacterSearchResponse takes an array of CharacterSearchResult');
            }
        }

        $this->page = $page;
        $this->totalResults = $totalResults;
    }

    public function addResult(CharacterSearchResult $result)
    {
        $this->results[] = $result;
        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $totalResults
     */
    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }


}