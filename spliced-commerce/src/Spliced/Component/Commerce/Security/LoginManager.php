<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security;

use Spliced\Component\Commerce\Model\CustomerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * Abstracts process for manually logging in a user.
 *
 * Based of FOS User
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class LoginManager
{
    private $securityContext;
    private $userChecker;
    private $sessionStrategy;
    private $container;

    /**
     * Constructor
     * 
     * @param SecurityContextInterface
     * @param UserCheckerInterface
     * @param SessionAuthenticationStrategyInterface
     * @param ContainerInterface
     */
    public function __construct(SecurityContextInterface $context, UserCheckerInterface $userChecker,
                                SessionAuthenticationStrategyInterface $sessionStrategy,
                                ContainerInterface $container)
    {
        $this->securityContext = $context;
        $this->userChecker = $userChecker;
        $this->sessionStrategy = $sessionStrategy;
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    final public function loginUser($firewallName, CustomerInterface $customer, Response $response = null)
    {

        $this->userChecker->checkPostAuth($customer);

        $token = $this->createToken($firewallName, $customer);

        if ($this->container->isScopeActive('request')) {
            $this->sessionStrategy->onAuthentication($this->container->get('request'), $token);

            if (null !== $response) {
                $rememberMeServices = $this->container->get('security.authentication.rememberme.services.persistent.'.$firewallName);

                if ($rememberMeServices instanceof RememberMeServicesInterface) {
                    $rememberMeServices->loginSuccess($this->container->get('request'), $response, $token);
                }
            }
        }

        $this->securityContext->setToken($token);
    }

    /**
     * {@inheritDoc}
     */
    protected function createToken($firewall, CustomerInterface $customer)
    {
        return new UsernamePasswordToken($customer, null, $firewall, $customer->getRoles());
    }
}
