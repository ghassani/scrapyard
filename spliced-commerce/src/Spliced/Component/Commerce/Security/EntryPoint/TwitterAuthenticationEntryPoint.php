<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\EntryPoint;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Spliced\Component\Commerce\Security\Authentication\Client\TwitterApiClient;

/**
 * TwitterAuthenticationEntryPoint
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $facebook;
    protected $options;
    protected $permissions;

    /**
     * Constructor
     *
     * @param TwitterApiClient $twitter
     * @param array            $options
     */
    public function __construct(TwitterApiClient $twitter, array $options = array(), array $permissions = array())
    {
        $this->twitter = $twitter;
        $this->permissions = $permissions;
        $this->options = new ParameterBag($options);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $authURL = $this->twitter->getLoginUrl();

        if (!$authURL) {
            throw new ConnectionException('Could not connect to Twitter!');
        }

        return new RedirectResponse($authURL);
    }
}
