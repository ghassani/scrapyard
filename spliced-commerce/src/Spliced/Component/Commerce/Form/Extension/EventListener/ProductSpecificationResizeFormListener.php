<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Extension\EventListener;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Spliced\Component\Commerce\Form\Type\Admin\ProductSpecificationType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\ProductInterface;

/**
 * ProductSpecificationResizeFormListener
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductSpecificationResizeFormListener extends ResizeFormListener
{

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param ProductInterface $product
     * 
     * {@inheritDoc}
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product, array $options = array(), $allowAdd = false, $allowDelete = false, $deleteEmpty = false)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
        parent::__construct(null, $options, $allowAdd, $allowDelete, $deleteEmpty);
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
     * {@inheritDoc}
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    
        if (null === $data) {
            $data = array();
        }
    
        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }
    
        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }
    
        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {

            $form->add($name, new ProductSpecificationType($this->getConfigurationManager(), $this->getProduct(), $value), array_replace(array(
                'property_path' => '['.$name.']',
            ), $this->options));
        }

    }
    
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    
        if (null === $data || '' === $data) {
            $data = array();
        }
    
        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }
    
        // Remove all empty rows
        if ($this->allowDelete) {
            foreach ($form as $name => $child) {
                if (!isset($data[$name])) {
                    $form->remove($name);
                }
            }
        }
    
        // Add all additional rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                
                if (!$form->has($name)) {
                    $form->add($name, new ProductSpecificationType($this->getConfigurationManager(), $this->getProduct()), array_replace(array(
                        'property_path' => '['.$name.']',
                    ), $this->options));
                }
            }
        }
    }

}