<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * PayPalUserToken
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalUserToken extends AbstractToken
{
    /** @var */
    private $providerKey;
    
    /** @var */
    protected $accessToken;

    /**
     * Constructor
     * 
     * @param string $uid
     * @param array $roles
     * @param string $authCode
     * @param string $accessToken
     */
    public function __construct($uid = '', array $roles = array(), $authCode = null, $accessToken = null)
    {
        parent::__construct($roles);

        $this->setUser($uid);

        if (!empty($uid)) {
            $this->setAuthenticated(true);
        }

        $this->authCode = $authCode;
        $this->accessToken = $accessToken;
    }

    /**
     * getCredentials
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * getAccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /**
     * getAuthCode
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * serialize
     */
    public function serialize()
    {
        return serialize(array($this->authCode, $this->accessToken, parent::serialize()));
    }

    /**
     * unserialize
     */
    public function unserialize($str)
    {
        list($this->authCode, $this->accessToken, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
