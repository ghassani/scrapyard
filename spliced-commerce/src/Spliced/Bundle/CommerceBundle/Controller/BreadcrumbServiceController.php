<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Spliced\Component\Commerce\Controller\ServiceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spliced\Component\Commerce\Breadcrumb\BreadcrumbManager;

/**
 * BreadcrumbServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class BreadcrumbServiceController extends ServiceController
{

    /**
     * Constructor
     * 
     * @param BreadcrumbManager $breadcrumbManager
     */
    public function __construct(BreadcrumbManager $breadcrumbManager)
    {
        $this->breadcrumbManager = $breadcrumbManager;
    }

    /**
     * getBreadcrumbManager
     * 
     * @return BreadcrumbManager
     */
     protected function getBreadcrumbManager()
     {
         return $this->breadcrumbManager;
     }
     
    /**
     * defaultAction
     */
    public function defaultAction()
    {
        return array('breadcrumbs' => $this->getBreadcrumbManager()->getBreadcrumbs());
    }
}
