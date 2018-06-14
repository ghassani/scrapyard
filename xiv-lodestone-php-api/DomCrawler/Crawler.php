<?php

namespace Spliced\Lodestone\DomCrawler;

use Symfony\Component\DomCrawler\Crawler as BaseCrawler;


class Crawler extends BaseCrawler
{

    public function extract($attribute, $asString = false)
    {
        return true === $asString ? implode(null, parent::extract($attribute)) : parent::extract($attribute);
    }

}