<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Doctrine\ORM\EntityManager;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;

/**
 * Router
 *
 * Adds dynamic routes by looking up available routes in the database.
 *
 * Symfony default router is first checked to match a route, and if none
 * is found then the database is checked for a matching route. If none is
 * found, 404 error will be encountered.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Router implements RouterInterface
{

    /** ConfigurationManager */
    protected $configurationManager;

    /** EntityManager */
    protected $em;

    /** RouterInterface */
    protected $parentRouter;

    /**
     * Constructor.
     *
     * @param RouterInterface $parentRouter
     * @param EntityManager $em
     * @param Session $session
     * @param AffiliateManager $affiliateManager
     * @param KernelInterface $kernel
     */
    public function __construct(RouterInterface $parentRouter, ConfigurationManagerInterface $configurationManager, EntityManager $em)
    {
        $this->parentRouter = $parentRouter;
        $this->em = $em;
        $this->configurationManager = $configurationManager;
    }

    /**
     * getParentRouter
     *
     * @return RouterInterface
     */
    protected function getParentRouter()
    {
        return $this->parentRouter;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getSession
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * getEntityManager
     *
     * @return getEntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteCollection()
    {
        return $this->getParentRouter()->getRouteCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->getParentRouter()->setContext($context);
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->getParentRouter()->getContext();
    }

    /**
     * {@inheritDoc}
     */
    public function generate($name, $parameters = array(), $absolute = false)
    {
        
        try{
            $parentGenerated = $this->getParentRouter()->generate($name, $parameters, $absolute);
            return $parentGenerated;
        } catch(\Exception $e){
        
        }
        
        $routeCollection = $this->getRouteCollection();
        
        $routeCollection->add($name, new Route($name, array(), array('_scheme' => isset($parameters['_scheme']) ? $parameters['_scheme'] : 'http')));
        
        return $this->getGenerator($routeCollection)->generate($name, $parameters, $absolute);
    }

    /**
     * {@inheritDoc}
     */
    public function match($url)
    {
        parse_str($_SERVER['QUERY_STRING'], $queryStringParams);
        // parent router is our first choice to save a db query if it is not needed
        try{
            $parentMatch = $this->getParentRouter()->match($url);
            return $parentMatch;
        }catch(ResourceNotFoundException $e){ /* DO NOTHING */}

        try {
            $route = $this->getEntityManager()
                ->getRepository('SplicedCmsBundle:Route')
                ->findOneByRequestPath($url);
            if(!$route){
                throw new ResourceNotFoundException();
            }
            
        } catch (NoResultException $e) {
            throw new ResourceNotFoundException();
        }
/*
        $targetPath = ;
        $routeOptions = ;
        if(isset($routeOptions['redirect']) && $routeOptions['redirect']) {
            header(
                "Location: ".$targetPath,
                true,
                isset($routeOptions['status']) ? $routeOptions['status'] : 307
            );
            exit();
        }
*/
        return array_merge(array(
            '_controller' => $route->getController(),
            '_route' => $route->getRequestPath(),
        ), $queryStringParams, $route->getOptions());
    }

    /**
     * {@inheritDoc}
     */
    public function getMatcher(RouteCollection $collection)
    {
        return $this->getParentRouter()->getMatcher($collection);
    }

    /**
     * {@inheritDoc}
     */
    public function getGenerator(RouteCollection $collection)
    {
        return new UrlGenerator($collection, $this->getContext());
    }
}