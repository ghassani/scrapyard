<?php

namespace Spliced\Component\Commerce\Tests\Breadcrumb;

use Spliced\Component\Commerce\Breadcrumb\Breadcrumb;

class BreadcrumbTest extends \PHPUnit_Framework_TestCase
{

    public function testBreadcrumb()
    {
        $crumb1Data = array(
            'anchor' => 'Anchor 1',
            'title' => 'Title 1',
            'href' => '/href/to',
            'position' => 1,
        );
        $crumb2Data = array(
            'anchor' => 'Anchor 2',
            'title' => 'Title 2',
            'href' => '/href/to',
            'position' => 2,
        );

        $crumb1 = new Breadcrumb($crumb1Data['anchor'], $crumb1Data['title'], $crumb1Data['href'], $crumb1Data['position']);

        $this->assertEquals($crumb1Data['anchor'], $crumb1->getAnchor());
        $this->assertEquals($crumb1Data['title'], $crumb1->getTitle());
        $this->assertEquals($crumb1Data['href'], $crumb1->getHref());
        $this->assertEquals($crumb1Data['position'], $crumb1->getPosition());

        $crumb2 = new Breadcrumb($crumb2Data['anchor'], $crumb2Data['title'], $crumb2Data['href'], $crumb2Data['position']);

        $this->assertEquals($crumb2Data['anchor'], $crumb2->getAnchor());
        $this->assertEquals($crumb2Data['title'], $crumb2->getTitle());
        $this->assertEquals($crumb2Data['href'], $crumb2->getHref());
        $this->assertEquals($crumb2Data['position'], $crumb2->getPosition());

    }
}
