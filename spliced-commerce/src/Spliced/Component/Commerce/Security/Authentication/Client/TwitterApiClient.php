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

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use TwitterOAuth;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

/**
 * TwitterApiClient
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterApiClient implements ConfigurableInterface
{
    private $twitter;
    private $session;
    private $router;
    private $callbackRoute;
    private $callbackURL;

    public function __construct(ConfigurationManager $configurationManager, Session $session, HttpKernelInterface $kernel)
    {        
        $this->kernel = $kernel;
        $this->session = $session;
        $this->configurationManager = $configurationManager;
        
        $this->twitter = new TwitterOAuth(
            $this->getOption('consumer_key'),
            $this->getOption('consumer_secret'),
            $this->getOption('oauth_token'),
            $this->getOption('oauth_token_secret')
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
     * getSession
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }
    
    public function setCallbackRoute(RouterInterface $router, $routeName)
    {
        $this->router = $router;
        $this->callbackRoute = $routeName;
    }

    public function getLoginUrl()
    {
        /* Get temporary credentials. */
        $requestToken = ($callbackUrl = $this->getCallbackUrl()) ?
            $this->twitter->getRequestToken($callbackUrl)
            : $this->twitter->getRequestToken();

        if (!isset($requestToken['oauth_token']) || !isset($requestToken['oauth_token_secret'])) {
            throw new \RuntimeException('Failed to validate oauth signature and token.');
        }

        /* Save temporary credentials to session. */
        $this->session->set('oauth_token', $requestToken['oauth_token']);
        $this->session->set('oauth_token_secret', $requestToken['oauth_token_secret']);

        /* If last connection failed don't display authorization link. */
        switch ($this->twitter->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $redirectURL = $this->twitter->getAuthorizeURL($requestToken);

                return $redirectURL;
                break;
            default:
                /* return null if something went wrong. */

                return null;
        }
    }

    public function getAccessToken($oauthToken, $oauthVerifier)
    {
        //set OAuth token in the API
        $this->twitter->setOAuthToken($oauthToken, $this->session->get('oauth_token_secret'));

        /* Check if the oauth_token is old */
        if ($this->session->has('oauth_token')) {
            if ($this->session->get('oauth_token') && ($this->session->get('oauth_token') !== $oauthToken)) {
                $this->session->remove('oauth_token');

                return null;
            }
        }

        /* Request access tokens from twitter */
        $accessToken = $this->twitter->getAccessToken($oauthVerifier);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $this->session->set('access_token', $accessToken['oauth_token']);
        $this->session->set('access_token_secret', $accessToken['oauth_token_secret']);

        /* Remove no longer needed request tokens */
        !$this->session->has('oauth_token') ?: $this->session->remove('oauth_token', null);
        !$this->session->has('oauth_token_secret') ?: $this->session->remove('oauth_token_secret', null);

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $this->twitter->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */

            return $accessToken;
        }

        /* Return null for failure */

        return null;
    }

    /**
     * getCallbackUrl
     */
    private function getCallbackUrl()
    {
        return sprintf('%s%stwitter_login_check',
            $this->getConfigurationManager()->get('commerce.store.url_secure'),
            $this->getKernel()->getEnvironment() == 'dev' ? $_SERVER['SCRIPT_NAME'].'/' : '/'
        );
        /*
        if (!empty($this->callbackRoute)) {
            return $this->router->generate($this->callbackRoute, array(), true);
        }
        */
        return null;
    }
    

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.twitter';
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
                'child_group' => 'Twitter',
                'position' => 0,
                'required' => false,
            ),
            'consumer_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'Consumer Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Twitter',
                'position' => 1,
                'required' => false,
            ),
            'oauth_token' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'OAuth Token',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Twitter',
                'position' => 0,
                'required' => false,
            ),
            'oauth_token_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'OAuth Token Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Twitter',
                'position' => 1,
                'required' => false,
            ),
        );
    }   
}
