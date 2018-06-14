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
    const ORDER_MEMBERSHIP_ASC = 3;
    const ORDER_MEMBERSHIP_DESC = 4;
    const ORDER_DATE_FOUNDED_ASC = 5;
    const ORDER_DATE_FOUNDED_DESC = 6;

    protected $uri = '/freecompany/';

    protected $method = 'GET';

    protected $query;

    protected $world;

    protected $focuses = array();

    protected $roles = array();

    protected $page = 1;

    protected $perPage = 50;

    protected $active;

    protected $recruitment;

    protected $housing;

    protected $grandCompanties = array();

    protected  $order = self::ORDER_NAME_ASC;

    public function __construct($query)
    {
        $this->query = $query;
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


        if ($this->getWorld()) {
            $query->set('worldname', $this->getWorld());
        }

        if ($this->getGrandCompanies()) {
            $query->set('gcid', $this->getGrandCompanies());
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

            $loadUrl = $searchResultCrawler->filter('.player_name_area a')->extract('href', true);
            $characterName = $searchResultCrawler->filter('.player_name_area a')->extract('_text', true);
            $characterWorld = $searchResultCrawler->filter('.player_name_area span[class!="right_txt"]')->extract('_text', true);
            $languages = $searchResultCrawler->filter('.player_name_area span[class="right_txt"]')->extract('_text', true);
            $classImageUrl = $searchResultCrawler->filter('.ic_class img')->extract('src', true);
            $thumbnail = $searchResultCrawler->filter('.thumb_cont_black_50 img')->extract('src', true);


            $character->setId(preg_replace('/[^0-9]/i', '', $loadUrl))
                ->setName($characterName)
                ->setWorld(str_replace(array(')','('), null, $characterWorld))
                ->setLanguages(explode(',', $languages))
                ->setThumbnail($thumbnail);
            //->setClass($classImageUrl);

            $response->addResult($character);
        }

        return $response;
    }


}