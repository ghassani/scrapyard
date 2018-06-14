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

use Spliced\Bundle\CmsBundle\Manager\SiteManager;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

class RouteProvider implements RouteProviderInterface
{
    public function __construct(EntityManager $em, SiteManager $siteManager)
    {
        $this->repository = $em->getRepository('SplicedCmsBundle:Route');
        $this->siteManager = $siteManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $domain = $request->server->get('SERVER_NAME');
        
        $site = $this->siteManager->findSiteByDomain($domain);
        
        if (!$site) {
            throw new \RuntimeException('Site not found or default was not set or is invalid.');
        }
        
        $this->siteManager->setCurrentSite($site);
        
        $collection = new RouteCollection();
        
        $uri = preg_replace('/^\/(.*\.php)(\/*)/', '/', $request->server->get('REQUEST_URI'));
        
        $route = $this->repository->findOneBy(array(
            'requestPath' => $uri,
            'site' => $this->siteManager->getCurrentSite()
        ));
        
        if (!$route) {
            return $collection;
        }
        
        $parameters = array_merge($route->getParameters(), array('_controller' => $route->getTargetPath()));
        
        if ($route->getPageId()) {
            $parameters['id'] = $route->getPageId();
        }
        
        $collection->add($route->getName(), new Route($route->getRequestPath(), $parameters));
        
        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutesByNames($names)
    {
        
        $collection = new RouteCollection();
        
        if (is_null($names)) {
            $routes = $this->repository->createQueryBuilder('r')
                ->getQuery()
                ->getResult();
        } else {
            $routes = $this->repository->findBy(array(
                'name' => $names
            ));
        }

        if (!count($routes)) {
            return $collection;
        }

        foreach ($routes as $route) {
            $parameters = array_merge($route->getParameters(), array('_controller' => $route->getTargetPath()));
            
            if ($route->getPageId()) {
                $parameters['id'] = $route->getPageId();
            }

            $collection->add($route->getName(), new Route($route->getRequestPath(), $parameters));
        }

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteByName($name)
    {
        $route = $this->repository->findOneBy(array(
            'name' => $name,
            'site' => $this->siteManager->getCurrentSite()
        ));
        
        if (!$route) {
            throw new RouteNotFoundException();
        }
        
        $parameters = array_merge($route->getParameters(), array('_controller' => $route->getTargetPath()));
        
        if ($route->getPageId()) {
            $parameters['id'] = $route->getPageId();
        }

        return new Route($route->getRequestPath(), $parameters);
    }
} 