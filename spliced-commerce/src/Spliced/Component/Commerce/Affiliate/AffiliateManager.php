<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Affiliate;


use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * AffiliateManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AffiliateManager
{
    const SESSION_TAG_AFFILIATE_ID = 'commerce.customer.affilliate_id';

    /**
     * @var Collection $affiliates
     */
    protected $affiliates = null;
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager
     * @param ObjectManager
     * @param Session
     */
    public function __construct(ConfigurationManager $configurationManager, Session $session)
    {
        $this->session = $session;
        $this->configurationManager = $configurationManager;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getEntityManager
     *
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getConfigurationManager()->getEntityManager();
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
     * setCurrentAffiliateId
     *
     * @param int $affiliateId
     */
    public function setCurrentAffiliateId($affiliateId)
    {
        $this->session->set(static::SESSION_TAG_AFFILIATE_ID, $affiliateId);

        return $this;
    }

    
    /**
     * getCurrentAffiliateId
     *
     * @return int|null
     */
    public function getCurrentAffiliateId()
    {
        return $this->session->get(static::SESSION_TAG_AFFILIATE_ID, null);
    }

    /**
     * getAffiliates
     */
    public function getAffiliates()
    {
        if($this->affiliates instanceof Collection){
            return $this->affiliates;
        }
        
        $this->affiliates = $this->getEntityManager()
        ->getRepository($this->getConfigurationManager()
        ->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_AFFILIATE))
        ->findByIsActive(true);
        
        
        return $this->affiliates;
    }

    /**
     * findAffiliateById
     *
     * @param int $id
     */
    public function findAffiliateById($id)
    {
        foreach ($this->getAffiliates() as $affiliate) {
            if ($affiliate->getId() == $id) {
                return $affiliate;
            }
        }

        return null;
    }
    
    /**
     * findAffiliateByName
     *
     * @param string $name
     */
    public function findAffiliateByName($name)
    {
        foreach ($this->getAffiliates() as $affiliate) {
            if (strtolower($affiliate->getName()) == strtolower($name)) {
                return $affiliate;
            }
        }
    
        return null;
    }
    
    /**
     * findAffiliateByUrl
     *
     * @param string $url
     */
    public function findAffiliateByUrl($url)
    {
        foreach ($this->getAffiliates() as $affiliate) {
            foreach ($affiliate->getReferrerUrls() as $expression) {
                $expression = sprintf('/%s/i',$expression);
                if (preg_match($expression,$url)) {
                    return $affiliate;
                }
            }
        }

        return null;
    }

    /**
     * findAffiliateByUrlPrefix
     *
     * @param string $urlPrefix
     */
    public function findAffiliateByUrlPrefix($urlPrefix)
    {
        foreach ($this->getAffiliates() as $affiliate) {
            if ($affiliate->getUrlPrefix() == $urlPrefix) {
                return $affiliate;
            }
        }

        return null;
    }
}
