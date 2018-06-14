<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Affiliate
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Table(name="affiliate")
 * @ORM\Entity()
 */
abstract class Affiliate implements AffiliateInterface
{ 
    
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    protected $website;

    /**
     * @var string $name
     *
     * @ORM\Column(name="order_prefix", type="string", length=10, nullable=true)
     */
    protected $orderPrefix;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="referer_urls", type="array", nullable=true)
     */
    protected $refererUrls;

    /**
     * @var string $name
     *
     * @ORM\Column(name="is_comissioned", type="boolean", nullable=true)
     */
    protected $isComissioned;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    protected $isActive;
    
    /**
     * getId
     *
     * @param string $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setName
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setWebsite
     *
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * getWebsite
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * setIsComissioned
     *
     * @param boolean $isComissioned
     */
    public function setIsComissioned($isComissioned)
    {
        $this->isComissioned = $isComissioned;
        return $this;
    }

    /**
     * getIsComissioned
     *
     * @return boolean
     */
    public function getIsComissioned()
    {
        return $this->isComissioned;
    }

    /**
     * setIsActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * getIsActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * setOrderPrefix
     *
     * @param string $urlPrefix
     */
    public function setOrderPrefix($orderPrefix)
    {
        $this->orderPrefix = $orderPrefix;
    }
    
    /**
     * getOrderPrefix
     *
     * @return string
     */
    public function getOrderPrefix()
    {
        return $this->orderPrefix;
    }
    
    /**
     * getRefererUrls
     * 
     * @return string
     */
    public function getRefererUrls()
    {
        return $this->refererUrls;

    }
    
    /**
     * setRefererUrls
     * 
     * @param array $refererUrls
     */
    public function setRefererUrls(array $refererUrls)
    {
        $this->refererUrls = $refererUrls;
        return $this;
    }
    
}