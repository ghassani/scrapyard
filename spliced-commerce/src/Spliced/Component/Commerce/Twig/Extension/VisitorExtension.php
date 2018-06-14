<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;

/**
 * VisitorExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class VisitorExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'parse_user_agent' => new \Twig_Function_Method($this, 'parseUserAgent'),
            'parse_url' => new \Twig_Function_Method($this, 'parseUrl'),
        );
    }

    /**
     * parseUrl
     * 
     * @param string $url
     */
    public function parseUrl($url)
    {
        return parse_url($url);
    }
    
    /**
     * parseUserAgent
     * 
     * @param string $string
     * @param bool $toString
     */
    public function parseUserAgent($string, $toString = false)
    {
        if($toString === true) {
            $data = UserAgentHelper::parseUserAgent($string);
            return $data['browser'].' '.$data['version'];
        }
        return UserAgentHelper::parseUserAgent($string);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_visitor';
    }

}
