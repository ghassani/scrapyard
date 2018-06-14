<?php


namespace Spliced\Lodestone\Request;

use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use Spliced\Lodestone\DomCrawler\Crawler;
use Spliced\Lodestone\Response\CharacterSearchResponse;
use Spliced\Lodestone\Model\CharacterSearchResult;

class FreeCompanyMemberListRequest implements RequestInterface {

    const ORDER_RANK_DESC          = 1;
    const ORDER_RANK_ASC           = 2;
    const ORDER_NAME_DESC          = 3;
    const ORDER_NAME_ASC           = 4;
    const ORDER_GRAND_COMPANY_ASC  = 5;

    protected $uri = '/freecompany/%s/member';

    protected $method = 'GET';

    protected $id;

    protected $page;

    protected $order = self::ORDER_RANK_DESC;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return sprintf($this->uri, $this->getId());
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function buildRequest(GuzzleRequestInterface $request)
    {
        if (!$this->id) {
            throw new \UnexpectedValueException(sprintf('FreeCompanyMemberListRequest requires a free company ID.'));
        }

        $query = $request->getQuery();

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

        $freeCompanyName = $responseCrawler->filter('.ic_freecompany_box .pt4 a')->extract('_text', true);

        foreach ($responseCrawler->filter('.area_inner_body .table_black_border_bottom table tr ') as $searchResult) {

            $searchResultCrawler = new Crawler($searchResult);

            $character = new CharacterSearchResult();

            $loadUrl        = $searchResultCrawler->filter('.player_name_area a')->extract('href', true);
            $characterName  = $searchResultCrawler->filter('.player_name_area a')->extract('_text', true);
            $characterWorld = $searchResultCrawler->filter('.player_name_area span[class!="right_txt"]')->extract('_text', true);
            $classImageUrl  = $searchResultCrawler->filter('.ic_class img')->extract('src', true);
            $thumbnail      = $searchResultCrawler->filter('.thumb_cont_black_50 img')->extract('src', true);
            $fcRank         = $searchResultCrawler->filter('.fc_member_status')->extract('_text', true);
            $grandCompany   = explode('/', trim($searchResultCrawler->filter('.ic_gc')->extract('_text', true)));
            $classLevel     = $searchResultCrawler->filter('.lv_class')->extract('_text');

            if (is_array($classLevel)) {
                $classLevel = implode(',', $classLevel);
            }
            $character->setId(preg_replace('/[^0-9]/i', '', $loadUrl))
                ->setName($characterName)
                ->setWorld(str_replace(array(')','('), null, $characterWorld))
                ->setThumbnail($thumbnail)
                ->setFreeCompany($freeCompanyName)
                ->setFreeCompanyRank(trim($fcRank))
                ->setCurrentClassLevel($classLevel)
                ->setCurrentClassImage($classImageUrl)
                ->setGrandCompany(isset($grandCompany[0]) ? $grandCompany[0] : null)
                ->setGrandCompanyRank(isset($grandCompany[1]) ? $grandCompany[1] : null);

            $response->addResult($character);
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return mixed
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

} 