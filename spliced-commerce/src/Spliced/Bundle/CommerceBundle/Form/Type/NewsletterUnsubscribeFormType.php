<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * NewsletterUnsubscribeFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class NewsletterUnsubscribeFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', 'email');
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\NewsletterSubscriber'
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'newsletter_unsubscribe';
    }
}