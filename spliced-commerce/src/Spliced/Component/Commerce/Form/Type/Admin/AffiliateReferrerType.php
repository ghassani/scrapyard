<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AffiliateReferrerType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class AffiliateReferrerType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_affiliate_referrer';
    }
    

}