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
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;

/**
 * ProductFilterFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterFormType extends AbstractType
{
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param EntityManager        $em
     */
    public function __construct(ConfigurationManager $configurationManager, EntityManager $em)
    {
        $this->em = $em;
        $this->configurationManager = $configurationManager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $attributes = $this->em->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION))
         ->getFilterableAttributes();

       foreach ($attributes as $attribute) {
           switch ($attribute->getOptionType()) {
               case 1:
               default:
                   $builder->add($attribute->getName(), 'choice', array(
                       'label' => $attribute->getPublicLabel(),
                       'choices' => $this->getAttributeChoices($attribute),
                       'empty_value' => $attribute->getPublicLabel(),
                       'required' => false,
                       'expanded' => false,
                       'attr' => array(
                           'title' => $attribute->getPublicLabel(),
                           'data-width' => '180px',
                        )
                   ));
                   break;
               case 2:
                   $builder->add($attribute->getName(), 'choice', array(
                       'label' => $attribute->getPublicLabel(),
                       'choices' => $this->getAttributeChoices($attribute),
                       'empty_value' => $attribute->getPublicLabel(),
                       'required' => false,
                       'multiple' => true,
                       'expanded' => false,
                       'attr' => array(
                           'title' => $attribute->getPublicLabel(),
                           'data-selected-text-format' => "count>3",
                           'data-width' => '180px',
                        )
                   ));
                   break;
           }
       }

       //echo '<pre>'; \Doctrine\Common\Util\Debug::dump($attributes);die();
    }

    /**
     * getAttributeChoices
     *
     * @param ProductAttributeOptionInterface $attribute
     */
    protected function getAttributeChoices(ProductAttributeOptionInterface $attribute)
    {
       $return = array();
       foreach ($attribute->getValues() as $value) {
           $return[$value->getValue()] = $value->getValue();
       }

       return $return;
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
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'product_filter';
    }

    /**
     * getListMethods
     *
     * @return array
     */
    protected function getListMethods()
    {
        return array('list' => 'List', 'grid' => 'Grid');
    }

    /**
     *  getPerPageChoices
     *
     *  @return array
     */
    protected function getPerPageChoices()
    {
        return array(
            10 => 10,
            20 => 20,
            50 => 50,
            100 => 100
        );
    }

    /**
     * getOrderingChoices
     *
     * @return array
     */
    protected function getOrderingChoices()
    {
        return array(
            'price_asc'  => 'Price Low to High',
            'price_desc' => 'Price High to Low',
            'name_asc'   => 'Product Name Ascending',
            'name_desc'  => 'Product Name Descending',
            'sku_asc'    => 'Product Sku Ascending',
            'sku_desc'   => 'Product Sku Descending',
        );
    }
}
