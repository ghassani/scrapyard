<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use \SoapServer;

/**
 * SoapServiceController
 *
 * @Route("/service/soap")
 */
class SoapServiceController extends Controller
{    
    
    /**
     * Orders SOAP Server Entry
     *
     * @Route("/orders", name="commerce_admin_soap_service_orders")
     * @Method({"GET","POST"})
     */
    public function ordersAction()
    {
        ini_set("soap.wsdl_cache_enabled", $this->get('kernel')->getEnvironment() == 'prod'); 
    
        try{
            $server = new SoapServer(
                $this->get('kernel')
                 ->locateResource('@SplicedCommerceAdminBundle/Resources/config/wsdl/orders.wsdl')
            );
            
            $server->setObject($this->get('commerce.webservice.soap.orders'));

        } catch(\InvalidArgumentException $e) { // wsdl not found
            die($e->getMessage());
        } catch(\RuntimeException $e) { // invalid/unsafe characters
            die($e->getMessage());
        } catch(\Exception $e) { // other error
            die($e->getMessage());
        }
                
        return $this->handleResponse($server);
    }
    
    /**
     * Products SOAP Server Entry
     *
     * @Route("/products", name="commerce_admin_soap_service_products")
     * @Method({"GET","POST"})
     */
    public function productsAction()
    {
        ini_set("soap.wsdl_cache_enabled", $this->get('kernel')->getEnvironment() == 'prod');
    
        try{
            $server = new SoapServer(
                $this->get('kernel')
                  ->locateResource('@SplicedCommerceAdminBundle/Resources/config/wsdl/products.wsdl')
            );
    
            $server->setObject($this->get('commerce.webservice.soap.products'));
    
        } catch(\InvalidArgumentException $e) { // wsdl not found
            die($e->getMessage());
        } catch(\RuntimeException $e) { // invalid/unsafe characters
            die($e->getMessage());
        } catch(\Exception $e) { // other error
            die($e->getMessage());
        }
    
        return $this->handleResponse($server);
    }
    
    /**
     * handleResponse
     * 
     * @param SoapServer $server
     * 
     */
    protected function handleResponse(SoapServer $server)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        
        ob_start();
        $server->handle();
        $response->setContent(ob_get_clean());
        
        return $response;
    }
}