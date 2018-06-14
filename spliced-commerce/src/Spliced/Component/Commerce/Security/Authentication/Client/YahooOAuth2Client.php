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
 * YahooOAuth2Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class YahooOAuth2Client extends BaseOAuth2Client implements ConfigurableInterface
{
    const AUTH_ENDPOINT_URL = 'https://api.login.yahoo.com/oauth/v2/request_auth';
    const TOKEN_ENDPOINT_URL = 'https://api.login.yahoo.com/oauth/v2/get_token';
    const TOKEN_REQUEST_ENDPOINT_URL = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
    const USER_ENDPOINT_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';

    /**
     *Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param HttpKernelInterface $kernel
     */
    public function __construct(ConfigurationManager $configurationManager, HttpKernelInterface $kernel)
    {
        $this->configurationManager = $configurationManager;
        parent::__construct(
            $this->getOption('consumer_key'),
            $this->getOption('consumer_secret')
        );
        $this->kernel = $kernel;
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
     * getKernel
     * 
     * @return HttpKernelInterface
     */
    protected function getKernel()
    {
        return $this->kernel;
    }
    /**
     *
     */
    public function getLoginUrl()
    {
        return null; //TODO
    }

    /**
     *
     */
    public function getRequestToken()
    {
        array(
            'oauth_consumer_key' => null,
            'oauth_nonce    ' => null,
            'oauth_signature_method' => null,
            'oauth_signature' => null,
            'oauth_timestamp' => null,
            'oauth_version' => 1.0,
        );
    }

    /**
     * exchangeAuthTokenForAccessToken
     *
     * @param string $authCode
     */
    public function exchangeAuthTokenForAccessToken($authCode)
    {
        $retirectUri = sprintf('%s%syahoo_login_check',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getKernel()->getEnvironment() == 'dev' ? $_SERVER['SCRIPT_NAME'].'/' : '/'
        );
        
        $accessToken = $this->getAccessToken(self::TOKEN_ENDPOINT_URL, self::GRANT_TYPE_AUTH_CODE, array(
            'code' => $authCode,
            'redirect_uri' => $retirectUri,
        ));


        if (isset($accessToken['result']['error'])) {
            throw new OAuth2Exception("Auth Token Exchange Error: ".$accessToken['result']['error']);
        }

        $this->setAccessToken($accessToken['result']['access_token']);

        return $accessToken;
    }

    /**
     *
     */
    public function getUserProfile()
    {
        return $this->fetch(self::USER_ENDPOINT_URL, array(), self::HTTP_METHOD_GET);

    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.yahoo';
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
            'consumer_key' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Consumer Key',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Yahoo',
                'position' => 0,
                'required' => false,
            ),
            'consumer_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'Consumer Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Yahoo',
                'position' => 1,
                'required' => false,
            ),
            'application_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Application ID',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Yahoo',
                'position' => 0,
                'required' => false,
            ),
            'login_enabled' => array(
                'type' => 'boolean',
                'value' => true,
                'label' => 'Enable Customer Login',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Yahoo',
                'position' => 0,
                'required' => false,
            ),
        );
    }   
}
