<?php

namespace Spliced\Lodestone\Test\Request;

use Spliced\Lodestone\Client;
use Spliced\Lodestone\Request\CharacterRequest;
use Spliced\Lodestone\Response\CharacterResponse;
use Spliced\Lodestone\Model\Character;
use Spliced\Lodestone\Model\CharacterAttributes;
use Spliced\Lodestone\Model\CharacterEquipment;
use Spliced\Lodestone\Model\CharacterClasses;

class CharacterRequestTest extends \PHPUnit_Framework_TestCase {

    public function testFunctionality()
    {
        $testId = 318035; // Rafah Qadriyah Diabolos

        $request = new CharacterRequest($testId);

        $this->assertEquals($testId, $request->getId());

        $request->setId($testId+1);

        $this->assertEquals($testId+1, $request->getId());
    }

    public function testResponse()
    {
        $testId = 318035; // Rafah Qadriyah Diabolos

        $client = new Client(Client::BASE_URL_NA);

        $request = new CharacterRequest($testId);

        $response = $client->send($request);

        $this->assertInstanceOf('\Spliced\Lodestone\Response\CharacterResponse', $response);
        $this->assertInstanceOf('\Spliced\Lodestone\Model\Character', $response->getResults());

        $character = $response->getResults();

        $this->assertInstanceOf('\Spliced\Lodestone\Model\CharacterAttributes', $character->getAttributes());

        $this->assertEquals($character->getId(), $testId);

        foreach ($character->getMinions() as $minion) {
            $this->assertInstanceOf('\Spliced\Lodestone\Model\Minion', $minion);
            $this->assertNotNull($minion->getName());
            $this->assertNotNull($minion->getThumbnail());
        }

        foreach ($character->getMounts() as $mount) {
            $this->assertInstanceOf('\Spliced\Lodestone\Model\Mount', $mount);
            $this->assertNotNull($mount->getName());
            $this->assertNotNull($mount->getThumbnail());
        }

        $this->assertInstanceOf('\Spliced\Lodestone\Model\CharacterClasses', $character->getClasses());

        $characterClasses = $character->getClasses();


        foreach ($characterClasses->getClasses() as $className => $characterClass) {
            $this->assertInstanceOf('\Spliced\Lodestone\Model\CharacterClass', $characterClass);
        }

        $characterEquipment = $character->getEquipment();

        $this->assertInstanceOf('\Spliced\Lodestone\Model\CharacterEquipment', $characterEquipment);



        //foreach($characterEquipment->get)
        file_put_contents('C:/users/gassan/desktop/test.txt', print_r((array) $character, true));

    }
}
 