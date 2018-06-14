<?php

namespace Spliced\Lodestone\Test\Request;

use Spliced\Lodestone\Request\FreeCompanyMemberListRequest;
use Spliced\Lodestone\Response\FreeCompanyMemberListResponse;
use Spliced\Lodestone\Client;

class FreeCompanyMemberListRequestTest extends \PHPUnit_Framework_TestCase
{

    public function testFunctionality()
    {
        $testId = '9232097761132837659';

        $request = new FreeCompanyMemberListRequest($testId);

        $this->assertEquals($request->getId(), $testId);
    }

    public function testResponse()
    {
        $testId = '9232097761132837659';

        $client = new Client(Client::BASE_URL_NA);

        $request = new FreeCompanyMemberListRequest($testId);

        $response = $client->send($request);


        file_put_contents('D:\Projects\LodestoneAPI\test.sphp', print_r((array) $response, true));

    }

}