<?php

namespace Spliced\Lodestone\Request;

use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;
use Spliced\Lodestone\DomCrawler\Crawler;
use Spliced\Lodestone\Client;

interface RequestInterface
{
    public function getMethod();

    public function getUri();

    public function buildRequest(GuzzleRequestInterface $request);

    public function buildResponse(Crawler $responseCrawler);

}

