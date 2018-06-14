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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductServiceController extends ServiceController
{

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @Template("SplicedCommerceBundle:Product/Block:collection.html.twig")
     *
     * Loads products with on_front attribute
     *
     */
    public function getFrontProductsAction()
    {
        return array(
            'products' => array()/*$this->configurationManager->getDocumentManager()
        		->getRepository($this->configurationManager->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
        		->getProductsByAttribute('on_front', true),*/
        );
    }

}
