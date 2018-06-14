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
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * GoogleOAuth2Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleOAuth2Client extends BaseOAuth2Client implements ConfigurableInterface
{
    const AUTH_ENDPOINT_URL = 'https://accounts.google.com/o/oauth2/auth';
    const TOKEN_ENDPOINT_URL = 'https://accounts.google.com/o/oauth2/token';
    const USER_ENDPOINT_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';

    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManger
     * @param HttpKernelInterface $kernel
     */
    public function __construct(ConfigurationManager $configurationManager, HttpKernelInterface $kernel)
    {
        $this->configurationManager = $configurationManager;
        $this->kernel = $kernel;
        
        parent::__construct(
            $this->getOption('oauth_client_id'),
            $this->getOption('oauth_client_secret')
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
     * getLoginUrl
     * 
     * @return string
     */
    public function getLoginUrl()
    {
        
        $loginCheckUrl = sprintf('%s%sgoogle_login_check',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getKernel()->getEnvironment() == 'dev' ? $_SERVER['SCRIPT_NAME'].'/' : '/'
        );
        
        return $this->getAuthenticationUrl(self::AUTH_ENDPOINT_URL, $loginCheckUrl, array(
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ));

    } 

    /**
     * exchangeAuthTokenForAccessToken
     *
     * @param string $authCode
     * 
     * @return string $accessToken
     */
    public function exchangeAuthTokenForAccessToken($authCode)
    {
        $loginCheckUrl = sprintf('%s%sgoogle_login_check',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getKernel()->getEnvironment() == 'dev' ? $_SERVER['SCRIPT_NAME'].'/' : '/'
        );

        $accessToken = $this->getAccessToken(self::TOKEN_ENDPOINT_URL, self::GRANT_TYPE_AUTH_CODE, array(
            'code' => $authCode,
            'redirect_uri' => $loginCheckUrl
        ));
        
        if (isset($accessToken['result']['error'])) {
            throw new GoogleOAuth2Exception("Auth Token Exchange Error: ".$accessToken['result']['error']);
        }
        
        $this->setAccessToken($accessToken['result']['access_token']);

        return $accessToken;
    }

    /**
     * getUserProfile
     * 
     * @return array
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
        return 'commerce.google';
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
            'oauth_client_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'OAuth Client ID',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Google',
                'position' => 0,
                'required' => false,
            ),
            'oauth_client_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'OAuth Client Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Google',
                'position' => 1,
                'required' => false,
            ),
        );
    }   
}
