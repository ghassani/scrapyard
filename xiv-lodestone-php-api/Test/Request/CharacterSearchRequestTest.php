<?php

namespace Spliced\Lodestone\Test\Request;

use Spliced\Lodestone\Request\CharacterSearchRequest;
use Spliced\Lodestone\Response\CharacterSearchResponse;
use Spliced\Lodestone\Model\CharacterSearchResult;
use Spliced\Lodestone\Model\ClassJob;
use Spliced\Lodestone\Model\World;
use Spliced\Lodestone\Model\GrandCompany;
use Spliced\Lodestone\Model\Language;
use Spliced\Lodestone\Client;

class CharacterSearchRequestTest extends \PHPUnit_Framework_TestCase
{

    public function testFunctionality()
    {
        $request = new CharacterSearchRequest('Rafah Qadriyah');
        
        $request->setOrder(CharacterSearchRequest::ORDER_NAME_DESC)
            ->setPerPage(50)
            ->setPage(1)
            ->setClass(ClassJob::BLACK_MAGE)
            ->setWorld(World::DIABOLOS)
            ->setGrandCompanies(array(GrandCompany::ORDER_OF_THE_TWIN_ADDER))
            ->setLanguages(array(Language::EN));

        $this->assertInstanceOf('\Spliced\Lodestone\Request\CharacterSearchRequest', $request);
        $this->assertEquals($request->getQuery(), 'Rafah Qadriyah');
        $this->assertEquals($request->getOrder(), CharacterSearchRequest::ORDER_NAME_DESC);
        $this->assertEquals($request->getPage(), 1);
        $this->assertEquals($request->getPerPage(), 50);
        $this->assertEquals($request->getClass(), ClassJob::BLACK_MAGE);
        $this->assertEquals($request->getWorld(), World::DIABOLOS);
        $this->assertEquals($request->getGrandCompanies(), array(GrandCompany::ORDER_OF_THE_TWIN_ADDER));
        $this->assertEquals($request->getLanguages(), array(Language::EN));
    }

    public function testResponse()
    {
        $client = new Client(Client::BASE_URL_NA);

        $request = new CharacterSearchRequest('Rafah Qadriyah');

        $request->setOrder(CharacterSearchRequest::ORDER_NAME_DESC)
            ->setPerPage(50)
            ->setPage(1)
            ->setClass(ClassJob::BLACK_MAGE)
            ->setWorld(World::DIABOLOS)
            ->setGrandCompanies(array(GrandCompany::ORDER_OF_THE_TWIN_ADDER))
            ->setLanguages(array(Language::EN));

        $response = $client->send($request);

        $this->assertInstanceOf('\Spliced\Lodestone\Response\CharacterSearchResponse', $response);

        $this->assertEquals($response->getTotalResults(), 1);
        $this->assertEquals($response->getPage(), 1);

        foreach ($response->getResults() as $characterSearchResult) {
            $this->assertInstanceOf('\Spliced\Lodestone\Model\CharacterSearchResult', $characterSearchResult);
            $this->assertEquals($characterSearchResult->getName(), 'Rafah Qadriyah');
            $this->assertNotNull($characterSearchResult->getWorld());
            $this->assertNotNull($characterSearchResult->getLanguages());
            $this->assertNotNull($characterSearchResult->getThumbnail());
            $this->assertNotNull($characterSearchResult->getId());
        }

    }

} 