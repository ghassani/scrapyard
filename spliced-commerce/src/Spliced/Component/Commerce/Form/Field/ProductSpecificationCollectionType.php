<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Spliced\Component\Commerce\Form\Extension\EventListener\ProductSpecificationResizeFormListener;
use Spliced\Component\Commerce\Form\Type\Admin\ProductSpecificationType;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\ProductInterface;

/**
 * ProductSpecificationEditCollectionType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductSpecificationCollectionType extends AbstractType
{
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param ProductInterface $product
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
    }
   
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getProduct
     *
     * @return ProductInterface
     */
    protected function getProduct()
    {
        return $this->product;
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['allow_add'] && $options['prototype']) {
            $prototypeFormType = new ProductSpecificationType($this->getConfigurationManager(), $this->getProduct());
            $prototype = $builder->create($options['prototype_name'], $prototypeFormType, array_replace(array(
                'label' => $options['prototype_name'].'label__',
            ), $options['options']));
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new ProductSpecificationResizeFormListener(
            $this->getConfigurationManager(),
            $this->getProduct(),
            $options['options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
* {@inheritdoc}
*/
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'allow_add' => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
        ));

        if ($form->getConfig()->hasAttribute('prototype')) {
            $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
        }
    }

    /**
    * {@inheritdoc}
    */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototype') && $view->vars['prototype']->vars['multipart']) {
            $view->vars['multipart'] = true;
        }
    }

    /**
    * {@inheritdoc}
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $optionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $resolver->setDefaults(array(
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => true,
            'prototype_name' => '__name__',
            'options' => array(),
            'delete_empty' => false,
        ));

        $resolver->setNormalizers(array(
            'options' => $optionsNormalizer,
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'product_specification_collection';
    }
}