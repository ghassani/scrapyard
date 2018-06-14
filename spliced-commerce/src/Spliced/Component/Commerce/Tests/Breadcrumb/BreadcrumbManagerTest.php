<?php

namespace Spliced\Component\Commerce\Tests\Breadcrumb;

use Spliced\Component\Commerce\Breadcrumb\Breadcrumb;
use Spliced\Component\Commerce\Breadcrumb\BreadcrumbManager;

class BreadcrumbManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testBreadcrumbManager()
    {
        $breadcumbManager = new BreadcrumbManager();

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

        $breadcrumbManager->add($crumb1Data['anchor'], $crumb1Data['title'], $crumb1Data['href'], $crumb1Data['position']);
        $breadcrumbManager->add($crumb2Data['anchor'], $crumb2Data['title'], $crumb2Data['href'], $crumb2Data['position']);

        $this->assertEquals(count($breadcrumbManager->getBreadcrumbs()),2);

    }
}
