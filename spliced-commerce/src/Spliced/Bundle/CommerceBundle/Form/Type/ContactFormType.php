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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ContactFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContactFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', 'text', array('required' => true))
          ->add('email', 'email', array('required' => true))
          ->add('phone', 'text', array('required' => false))
          ->add('subject', 'choice', array(
              'required' => true, 
              'choices' => $this->getSubjects(),
              'empty_value' => '-Select a Subject-'
          ))
          ->add('comment', 'textarea', array('required' => true));
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\ContactMessage'
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'contact';
    }

    /**
     * getSubjects
     * 
     * @return array
     */
    protected function getSubjects()
    {
        return array(
            'General Inquiries' => 'General Inquiries',
            'Sales Issue' => 'Sales Issue',
            'Customer Support' => 'Customer Support',
            'Report a Bug' => 'Report a Bug',
        );
    }
}
