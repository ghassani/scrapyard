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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceBundle\Document;
use Symfony\Component\HttpFoundation\Response;

/**
 * DefaultController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/", name="commerce_homepage")
     * @Template()
     */
    public function indexAction()
    {
        
        return $this->render('SplicedCommerceBundle:Default:index.html.twig');
    }
    /**
     * @Route("/test-stuff", name="commerce_test_bed")
     * @Template()
     */
    public function testAction()
    {
        $client = new \Spliced\Component\Apple\iDevice\ActivationClient();
        
        echo '<pre>';
        foreach($this->get('commerce.checkout_manager')->getStepHandlersByStep(1) as $h){
            var_dump(array(
                'name' => $h->getName(),
                'step' => $h->getStep(),
                'priority' => $h->getPriority(),
            ));
        }
        die();
    }
}
