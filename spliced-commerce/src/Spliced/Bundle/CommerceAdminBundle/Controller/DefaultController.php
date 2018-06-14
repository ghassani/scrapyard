<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="commerce_admin_dashboard")
     * @Template()
     */
    public function indexAction()
    {

        /*$dm = $this->get('commerce.admin.document_manager');
        
        $testAttribute1 = $dm->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
          ->findOneByKey('operating_system');
        $testAttribute2 = $dm->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
          ->findOneByKey('metropcs_phone_sale');
        
        if(!$testAttribute1 || !$testAttribute2){
            die('attr not found');
        }
        
        // first create 2 new simple products 
        $simpleProduct1 = new \Spliced\Bundle\CommerceAdminBundle\Document\Product();
        $simpleProduct2 = new \Spliced\Bundle\CommerceAdminBundle\Document\Product();
        
        $simpleProduct1->setName('Simple Product 1')->setSku('SP1')->setPrice(2.00)->setCost(1.00);
        $simpleProduct2->setName('Simple Product 2')->setSku('SP2')->setPrice(2.00)->setCost(1.00);
        
        $dm->persist($simpleProduct1);
        $dm->persist($simpleProduct2);
        $dm->flush();
        
        $product = new \Spliced\Bundle\CommerceAdminBundle\Document\Product();
        $englishContent = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductContent('en');
        $spanishContent = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductContent('es');
        
        $attribute1 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductAttribute();
        $attribute1->setOption($testAttribute1)
          ->setOptionKey($testAttribute1->getKey())
          ->setOptionType($testAttribute1->getOptionType())
          ->setValues(array('Android'));
        
        $attribute2 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductAttribute();
        $attribute2->setOption($testAttribute2)
        ->setOptionKey($testAttribute2->getKey())
        ->setOptionType($testAttribute2->getOptionType())
        ->setValues(array()); 
        
        $bundledItem1 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductBundledItem();
        $bundledItem1->setProduct($simpleProduct1)
         ->setPriceAdjustment(1.00)->setPriceAdjustmentType(2);
        
        $bundledItem2 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductBundledItem();
        $bundledItem2->setProduct($simpleProduct2);
        
        $image1 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductImage();
        $image2 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductImage();
        
        $image1->setIsMain(true)->setFileName('file.jpg')->setFilePath('/path/to/file/');
        $image2->setIsMain(false)->setFileName('file2.jpg')->setFilePath('/path/to/file/');
        
        $tierPrice1 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductTierPrice();
        $tierPrice2 = new \Spliced\Bundle\CommerceAdminBundle\Document\ProductTierPrice();
        
        $tierPrice1->setMinQuantity(1)
          ->setMaxQuantity(2)
          ->setAdjustment(1.00)
          ->setAdjustmentType(2);
        
        $tierPrice2->setMinQuantity(3)
        ->setMaxQuantity(-1)
        ->setAdjustment(1.20)
        ->setAdjustmentType(2);
         
        $product->setName('Some Product')
          ->setSku('SOMEPRODUCT2')
          ->setPrice(1.00)
          ->setCost(0.50)
          ->addContent($englishContent)
          ->addContent($spanishContent)
          ->addAttribute($attribute1)
          ->addAttribute($attribute2)
          ->addImage($image1)
          ->addImage($image2)
          ->addBundledItem($bundledItem1)
          ->addBundledItem($bundledItem2)
          ->addTierPrice($tierPrice1)
          ->addTierPrice($tierPrice2)
        ; 

        $dm->persist($product);
        $dm->flush();
        $dm->clear();
        
        die('done');*/
        
        /*$em = $this->getDoctrine()->getManager();
        $pr = $this->get('commerce.product.repository');
        
        
        foreach($em->getRepository('SplicedCommerceAdminBundle:User')->findByEnabled(true) as $dUser){
            
            $user = new \Spliced\Bundle\CommerceAdminBundle\Document\User();
            $user->setEmail($dUser->getEmail())
              ->setPassword($dUser->getPassword())
              ->setSalt($dUser->getSalt())
              ->setRoles($dUser->getRoles())
              ->setEnabled(true);
            
            $dm->persist($user);
            
        }
        $dm->flush();
        die('done');*/ 
        
        
        $this->get('twig.extension.chart')->visitorBrowserChartDataByDateRange(new \DateTime('now -1 week'), new \DateTime('now'));
         
        $todaysOrders = $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Order')
          ->getTodaysOrders();
        
        $newCustomers = $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Customer')
          ->getRecentlyCreated();
        
        
        return array(
            'todaysOrders' => $todaysOrders,
            'newCustomers' => $newCustomers,
        );
    }
    
    /**
     * @Route("/user-agent-debug", name="commerce_admin_user_agent_debug")
     */
    public function userAgentDebugAction()
    {
        $userAgent = \Spliced\Component\Commerce\Helper\UserAgent::parseUserAgent($this->getRequest()->headers->get('user-agent'));
    }
}
