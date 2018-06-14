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

/**
 * LocaleExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LocaleExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'locale_get_display_name' => new \Twig_SimpleFunction('locale_get_display_name', 'locale_get_display_name'),
            'locale_get_display_language' => new \Twig_SimpleFunction('locale_get_display_language', 'locale_get_display_language'),
            'locale_lookup' => new \Twig_SimpleFunction('locale_lookup', 'locale_lookup'),
                
        );
    }
    

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_locale';
    }

}
