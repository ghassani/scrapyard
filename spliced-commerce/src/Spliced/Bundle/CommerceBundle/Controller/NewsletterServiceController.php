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
use Spliced\Bundle\CommerceBundle\Form\Type\NewsletterSubscribeFormType;
use Symfony\Component\Form\FormFactory;

/**
 * NewsletterServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class NewsletterServiceController extends ServiceController
{

    /**
     *
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * getFormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }
    
    /**
     * @Template("SplicedCommerceBundle:Newsletter:register_block.html.twig")
     *
     */
    public function registerBlockAction()
    {
        
        return array(
            'form' => $this->getFormFactory()->create(new NewsletterSubscribeFormType())->createView(),
        );
    }


}
