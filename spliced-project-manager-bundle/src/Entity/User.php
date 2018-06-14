<?php
namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
        
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function __toString(){
    	return $this->getUsername().' - '.$this->getEmail();
    }
    public function getExpiresAt(){
    	return $this->expiresAt;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getEnabled(){
    	return $this->isEnabled();
    }
    
    public function getListName(){
    	return sprintf('%s - %s (%s)',$this->getUsername(),$this->getEmail(),implode(',',$this->getRoles()));
    }
}