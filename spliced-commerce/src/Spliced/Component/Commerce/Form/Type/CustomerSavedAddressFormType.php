<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Spliced\Bundle\CommerceBundle\Entity;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

/**
 * CustomerSavedAddressFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerSavedAddressFormType extends AbstractType
{
    /**
     * @param SecurityContext $securityContext
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->securityContext->isGranted('ROLE_USER')) {
            $customer = $this->securityContext->getToken()->getUser();

            $builder->add('addresses', 'entity', array(
                'class' => 'SplicedCommerceBundle:CustomerAddress',
                'empty_value' => '-Use a Saved Address-',
                'query_builder' => function(EntityRepository $er) use ($customer) {
                    return $er->createQueryBuilder('a')
                    ->where('a.customer = :customer')
                    ->setParameter('customer',$customer)
                    ->orderBy('a.id', 'ASC');
                },
            ));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
        ));
    }

    /**
     *
     */
    public function getName()
    {
        return 'customer_saved_address';
    }

}
