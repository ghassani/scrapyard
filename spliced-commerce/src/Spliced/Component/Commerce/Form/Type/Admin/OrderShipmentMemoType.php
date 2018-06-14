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
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * OrderShipmentMemoType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderShipmentMemoType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('createdBy')
          ->add('trackingNumber', 'text')
          ->add('memo', 'textarea')
          ->add('notify', 'checkbox', array(
              'mapped' => false, 
              'required' => false, 
              'value' => 1
          ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_order_shipment_memo';
    }
}
