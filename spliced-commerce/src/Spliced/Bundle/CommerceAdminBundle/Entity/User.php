<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Spliced\Component\Commerce\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */ 
class User extends BaseUser 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Required")
     * @Assert\Email(message = "Invalid", checkMX = false)
     * @Assert\NotNull(message="Required", groups={"new_registration"})
     * @Assert\Email(message = "Invalid", checkMX = false, groups={"new_registration"})
     */
    protected $email;

    /**
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    protected $salt;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @Assert\NotNull(message="Required", groups={"new_registration"})
     */
    protected $plainPassword;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked;

    /**
     * @ORM\Column(name="expired", type="boolean")
     */
    protected $expired;

    /**
     * @ORM\Column(name="expires_at", type="datetime")
     */
    protected $expiresAt;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(name="credentials_expired", type="boolean")
     */
    protected $credentialsExpired;

    /**
     * @ORM\Column(name="credentials_expire_at", type="datetime", nullable=true)
     */
    protected $credentialsExpireAt;

    

    /**
     * isPasswordValid
     *
     * @param ExecutionContext $context
     */
    public function isPasswordValid(ExecutionContext $context)
    {
        $plainPassword = $this->getPlainPassword();

        if (strlen($plainPassword) > 6) {
            $context->addViolationAt('plainPassword', 'Must be at least 6 characters long.', array(), null);
        }

        if (!preg_match('/[^A-Z]{1,}/i',$plainPassword)) {
            $context->addViolationAt('plainPassword', 'Must be at least contain at least one A-Z character.', array(), null);
        }

    }
}
