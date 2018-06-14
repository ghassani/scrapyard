<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Authentication\Client;

use OAuth2\Client as BaseOAuth2Client;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

/**
 * PayPalOAuth2Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalOAuth2Client extends BaseOAuth2Client implements ConfigurableInterface
{
    const AUTH_ENDPOINT_URL     = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
    const LOGOUT_ENDPOINT_URL     = 'https://www.paypal.com/webapps/auth/logout';
    const TOKEN_ENDPOINT_URL     = 'https://api.paypal.com/v1/identity/openidconnect/tokenservice';
    const USER_ENDPOINT_URL     = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo';
 
    const SANDBOX_AUTH_ENDPOINT_URL     = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
    const SANDBOX_LOGOUT_ENDPOINT_URL     = 'https://www.sandbox.paypal.com/webapps/auth/logout';
    const SANDBOX_TOKEN_ENDPOINT_URL     = 'https://api.sandbox.paypal.com/v1/identity/openidconnect/tokenservice';
    const SANDBOX_USER_ENDPOINT_URL     = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo';
    
    /**
     * @parameter ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, HttpKernelInterface $kernel)
    {
        $this->configurationManager = $configurationManager;
        $this->kernel = $kernel;

        parent::__construct(
            $this->getOption('app_id'),
            $this->getOption('app_secret')
        );
    }

    /**
     * getKernel
     * 
     * @return HttpKernelInterface
     */
    protected function getKernel()
    {
        return $this->kernel;
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
     *
     */
    public function getLoginUrl()
    {
        return $this->getAuthenticationUrl(
            $this->getOption('sandbox', true) ? static::SANDBOX_AUTH_ENDPOINT_URL : static::AUTH_ENDPOINT_URL,
            $this->getLoginCheckUrl(), 
            array(
                'scope' => 'profile email address'
            )
        );
    }

    /**
     * exchangeAuthTokenForAccessToken
     *
     * @param string $authCode
     */
    public function exchangeAuthTokenForAccessToken($authCode)
    {
        $accessToken = $this->getAccessToken(
            $this->getOption('sandbox', true) ? static::SANDBOX_TOKEN_ENDPOINT_URL : static::TOKEN_ENDPOINT_URL,
            self::GRANT_TYPE_AUTH_CODE, 
            array(
                'code' => $authCode,
                'redirect_uri' => $this->getLoginCheckUrl(),
            )
        );

        if (isset($accessToken['result']['error'])) {
            throw new PayPalOAuth2Exception("Auth Token Exchange Error: ".$accessToken['result']['error']);
        }

        $this->setAccessToken($accessToken['result']['access_token']);
        
        return $accessToken;
    }

    /**
     * getLoginCheckUrl
     * 
     * @return string
     */
    protected function getLoginCheckUrl()
    {

        return sprintf('%s%spaypal_login_check',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getKernel()->getEnvironment() == 'dev' ? $_SERVER['SCRIPT_NAME'].'/' : '/'
        );
    }

    /**
     * getUserProfile
     * 
     * @return array
     */
    public function getUserProfile()
    {
        return $this->fetch(
            $this->getOption('sandbox', true) ? static::SANDBOX_USER_ENDPOINT_URL : static::USER_ENDPOINT_URL, 
            array('schema' => 'openid'), 
            self::HTTP_METHOD_GET
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.paypal_login';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getConfigurationManager()->getByKeyExpression(sprintf('/^%s/',$this->getConfigPrefix()));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOption($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get(sprintf('%s.%s',$this->getConfigPrefix(),$key),$defaultValue);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {

        return array(
            'app_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Client ID',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'PayPal',
                'position' => 0,
                'required' => false,
            ),
            'app_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'Client Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'PayPal',
                'position' => 1,
                'required' => false,
            ),
            'sandbox' => array(
                'type' => 'boolean',
                'value' => true,
                'label' => 'Sandbox Mode',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'PayPal',
                'position' => 2,
                'required' => false,
            ),
        );
    }   
}
