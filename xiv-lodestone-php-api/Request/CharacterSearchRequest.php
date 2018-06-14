<?php

namespace Spliced\Lodestone\Request;

use Spliced\Lodestone\Model\CharacterSearchResult;
use Spliced\Lodestone\Response\CharacterSearchResponse;
use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use Spliced\Lodestone\DomCrawler\Crawler;
use Spliced\Lodestone\Model\ClassJob;

class CharacterSearchRequest implements RequestInterface
{
    const ORDER_NAME_ASC = 1;
    const ORDER_NAME_DESC = 2;
    const ORDER_WORLD_ASC = 3;
    const ORDER_WORLD_DESC = 4;

    protected $uri = '/character/';

    protected $method = 'GET';

    protected $class;

    protected $race;

    protected $page = 1;

    protected $perPage = 50;

    protected $grandCompanies = array();

    protected $languages = array();

    protected $order = self::ORDER_NAME_ASC;

    public function __construct($query, $world = null, $race = null, $class = null, array $grandCompanies = array(), array $languages = array())
    {
        $this->query = $query;
        $this->world = $world;
        $this->race = $race;
        $this->class = $class;
        $this->grandCompanies = $grandCompanies;
        $this->languages = $languages;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function buildRequest(GuzzleRequestInterface $request)
    {
        if (!$this->query) {
           throw new \UnexpectedValueException(sprintf('CharacterSearchRequest requires at least a search query.'));
        }

        $query = $request->getQuery();

        $query->set('q', $this->getQuery());

        if ($this->getClass()) {
            $query->set('classjob', $this->getClass());
        }

        if ($this->getWorld()) {
            $query->set('worldname', $this->getWorld());
        }

        if ($this->getRace()) {
            $query->set('race_tribe', $this->getRace());
        }

        if ($this->getGrandCompanies()) {
            $query->set('gcid', $this->getGrandCompanies());
        }

        if ($this->getLanguages()) {
            $query->set('blog_lang', $this->getLanguages());
        }

        if ($this->getPage()) {
            $query->set('page', $this->getPage());
        }

        if ($this->getOrder()) {
            $query->set('order', $this->getOrder());
        } else {
            $query->set('order', static::ORDER_NAME_ASC);
        }
    }

    public function buildResponse(Crawler $responseCrawler)
    {
        $response = new CharacterSearchResponse();

        $response->setPage($this->getPage())
          ->setTotalResults($responseCrawler->filter('div.pager .pagination span.total')->first()->extract('_text', true));

        foreach ($responseCrawler->filter('.area_inner_body .table_black_border_bottom table tr ') as $searchResult) {

            $searchResultCrawler = new Crawler($searchResult);

            $character = new CharacterSearchResult();

            $loadUrl        = $searchResultCrawler->filter('.player_name_area a')->extract('href', true);
            $characterName  = $searchResultCrawler->filter('.player_name_area a')->extract('_text', true);
            $characterWorld = $searchResultCrawler->filter('.player_name_area span[class!="right_txt"]')->extract('_text', true);
            $languages      = $searchResultCrawler->filter('.player_name_area span[class="right_txt"]')->extract('_text', true);
            $classImageUrl  = $searchResultCrawler->filter('.ic_class img')->extract('src', true);
            $thumbnail      = $searchResultCrawler->filter('.thumb_cont_black_50 img')->extract('src', true);
            $fcRank         = $searchResultCrawler->filter('.fc_member_status')->extract('_text', true);
            $grandCompany   = explode('/', trim($searchResultCrawler->filter('.ic_gc')->extract('_text', true)));
            $classLevel     = $searchResultCrawler->filter('.lv_class')->extract('_text');

            $character->setId(preg_replace('/[^0-9]/i', '', $loadUrl))
              ->setName($characterName)
              ->setWorld(str_replace(array(')','('), null, $characterWorld))
              ->setLanguages(explode(',', $languages))
              ->setThumbnail($thumbnail)
             # ->setFreeCompany($freeCompanyName)
              ->setFreeCompanyRank(trim($fcRank))
              ->setCurrentClassLevel($classLevel)
              ->setCurrentClassImage($classImageUrl)
              ->setGrandCompany($grandCompany[0])
              ->setGrandCompanyRank($grandCompany[1]);

            $response->addResult($character);
        }

        return $response;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $grandCompanies
     */
    public function setGrandCompanies(array $grandCompanies)
    {
        $this->grandCompanies = $grandCompanies;
        return $this;
    }

    /**
     * @return array
     */
    public function getGrandCompanies()
    {
        return $this->grandCompanies;
    }

    /**
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $race
     */
    public function setRace($race)
    {
        $this->race = $race;
    }

    /**
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param string $world
     */
    public function setWorld($world)
    {
        $this->world = $world;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorld()
    {
        return $this->world;
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
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }


}