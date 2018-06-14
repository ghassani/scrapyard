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

use Doctrine\Bundle\DoctrineBundle\Registry;
use Spliced\Component\Commerce\Controller\ServiceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * CategoryServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryServiceController extends ServiceController
{

    public function __construct(Registry $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @Template("SplicedCommerceBundle:Category:Blocks/list.html.twig")
     *
     * Loads all Categories with Their related children
     */
    public function listAction()
    {
        $categories = $this->orm
          ->getRepository('SplicedCommerceBundle:Category')
          ->getAllJoinedWithAllChildren();

        return array(
            'categories' => $categories
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Category:Blocks/menu.html.twig")
     *
     * Loads a Top Level Category Menu
     */
    public function menuAction()
    {
        $categories = $this->orm
          ->getRepository('SplicedCommerceBundle:Category')
          ->findByParent(null);

        return array(
            'categories' => $categories
        );
    }

    /**
     * filterBlockAction
     *
     * @Template("SplicedCommerceBundle:Category/Blocks:filter.html.twig")
     */
    public function filterBlockAction()
    {

    }

}
