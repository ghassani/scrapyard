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

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

/**
 * Implements Symfony2 session persistence for Facebook.
 *
 * @edits Gassan Idriss
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class FacebookApiClient extends \BaseFacebook implements ConfigurableInterface
{
    const PREFIX = '_commerce_facebook_';

    protected $session;
    protected $prefix;
    protected static $kSupportedKeys = array('state', 'code', 'access_token', 'user_id');

    /**
     * @param array $config the application configuration.
     * @see BaseFacebook::__construct in facebook.php
     */
    public function __construct(ConfigurationManager $configurationManager, Session $session, $prefix = self::PREFIX)
    {
        $this->session = $session;
        $this->prefix  = $prefix;

        $this->setAppId($configurationManager->get('commerce.facebook.api_key'));
        $this->setAppSecret($configurationManager->get('commerce.facebook.api_secret'));
        if ($configurationManager->has('commerce.facebook.api_uploads')) {
            $this->setFileUploadSupport($configurationManager->get('commerce.facebook.api_uploads'));
        }
        // Add trustProxy configuration
        // TODO: Update for 2.3 compatibility
        //$this->trustForwarded = $configurationManager->has('commerce.facebook.api_trust_forward') ? $configurationManager->get('commerce.facebook.api_trust_forward') : Request::isProxyTrusted();
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
     
    /**
     * getLoginUrl
     * 
     * @param array $params
     * 
     * @return string
     */
    public function getLoginUrl($params = array())
    {
        $this->establishCSRFTokenState();
        $currentUrl = $this->getCurrentUrl();

        // if 'scope' is passed as an array, convert to comma separated list
        $scopeParams = isset($params['scope']) ? $params['scope'] : null;
        if ($scopeParams && is_array($scopeParams)) {
            $params['scope'] = implode(',', $scopeParams);
        }

        return $this->getUrl(
            'www',
            'dialog/oauth',
            array_merge(
                array(
                    'client_id' => $this->getAppId(),
                    'redirect_uri' => $currentUrl, // possibly overwritten
                    'state' => $this->getState(),
                ),
                $params
            )
        );
    }

    protected function getCode()
    {
        if (isset($_REQUEST['code'])) {
            if ($this->getState() !== null &&
                isset($_REQUEST['state']) &&
                $this->getState() === $_REQUEST['state']) {

                    // CSRF state has done its job, so clear it
                    $this->setState(null);
                    $this->clearPersistentData('state');

                    return $_REQUEST['code'];
            } else {
                self::errorLog('CSRF state token does not match one provided.');

                return false;
            }
        }

        return false;
    }

    protected function establishCSRFTokenState()
    {
        if ($this->getState() === null) {
            $this->setState(md5(uniqid(mt_rand(), true)));
        }
    }

    /**
     * Stores the given ($key, $value) pair, so that future calls to
     * getPersistentData($key) return $value. This call may be in another request.
     *
     * @param string $key
     * @param array  $value
     *
     * @return void
     */
    protected function setPersistentData($key, $value)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to setPersistentData.');

            return;
        }

        $this->session->set($this->constructSessionVariableName($key), $value);
    }

    /**
     * Get the data for $key, persisted by BaseFacebook::setPersistentData()
     *
     * @param string  $key     The key of the data to retrieve
     * @param boolean $default The default value to return if $key is not found
     *
     * @return mixed
     */
    protected function getPersistentData($key, $default = false)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to getPersistentData.');

            return $default;
        }

        $sessionVariableName = $this->constructSessionVariableName($key);
        if ($this->session->has($sessionVariableName)) {
            return $this->session->get($sessionVariableName);
        }

        return $default;
    }

    /**
     * Clear the data with $key from the persistent storage
     *
     * @param  string $key
     * @return void
     */
    protected function clearPersistentData($key)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to clearPersistentData.');

            return;
        }

        $this->session->remove($this->constructSessionVariableName($key));
    }

    /**
     * Clear all data from the persistent storage
     *
     * @return void
     */
    protected function clearAllPersistentData()
    {
        foreach ($this->session->all() as $k => $v) {
            if (0 !== strpos($k, $this->prefix)) {
                continue;
            }

            $this->session->remove($k);
        }
    }

    protected function constructSessionVariableName($key)
    {
        return $this->prefix.implode(
            '_',
            array(
                'fb',
                $this->getAppId(),
                $key,
            )
        );
    }

    /**
     * getState
     */
    private function getState()
    {
        return $this->getPersistentData('state', null);
    }

    /**
     * setState
     */
    private function setState($state)
    {
        $this->setPersistentData('state', $state);
    }
    

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.facebook';
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
            'api_key' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'API Key',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Facebook',
                'position' => 0,
                'required' => false,
            ),
            'api_secret' => array(
                'type' => 'encrypted',
                'value' => null,
                'label' => 'API Secret',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Facebook',
                'position' => 1,
                'required' => false,
            ),
            'api_uploads' => array(
                'type' => 'boolean',
                'value' => false,
                'label' => 'Enable Uploads',
                'help' => '',
                'group' => 'Networks',
                'child_group' => 'Facebook',
                'position' => 2,
                'required' => false,
            ),
        );
    }   
}
