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
 * TwitterUserToken
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterUserToken extends AbstractToken
{
    private $oauthVerifier;

    public function __construct($uid = '', $oauthVerifier, array $roles = array())
    {
        parent::__construct($roles);

        $this->oauthVerifier = $oauthVerifier;
        $this->setUser($uid);

        if (!empty($roles)) {
            parent::setAuthenticated(true);
        }
    }

    public function setAuthenticated($bool)
    {
        if ($bool) {
            throw new \LogicException('TwitterUserToken may not be set to authenticated after creation.');
        }

        parent::setAuthenticated(false);
    }

    public function getCredentials()
    {
        return '';
    }

    public function getOauthVerifier()
    {
        return $this->oauthVerifier;
    }

    public function serialize()
    {
        return serialize(array(
                $this->oauthVerifier,
                parent::serialize(),
        ));
    }

    public function unserialize($str)
    {
        list($this->oauthVerifier, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
