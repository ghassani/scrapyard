<?php

namespace Spliced\Lodestone\Test;

use Spliced\Lodestone\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testClient()
    {
        $client = new Client(Client::BASE_URL_NA);

        $this->assertEquals($client->getBaseUrl(), Client::BASE_URL_NA);

        $client->setBaseUrl(Client::BASE_URL_JP);

        $this->assertEquals($client->getBaseUrl(), Client::BASE_URL_JP);

    }


} 