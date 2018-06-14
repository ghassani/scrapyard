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
 * ProductSortOrderFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSortOrderFormType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
        ->add('perPage', 'choice', array(
            'choices'   => $this->getPerPageChoices(),
            'required'  => false,
        ))
        ->add('orderChoices', 'choice', array(
            'choices'   => $this->getOrderingChoices(),
            'required'  => false,
        ))
        ->add('listMethod', 'choice', array(
            'choices'   => $this->getListMethods(),
            'required'  => false,
        ));
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
        return 'category_filter';
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
            'price_asc' => 'Price Low to High',
            'price_desc' => 'Price High to Low',
            'name_asc' => 'Product Name Ascending',
            'name_desc' => 'Product Name Descending',
            'sku_asc' => 'Product Sku Ascending',
            'sku_desc' => 'Product Sku Descending',
        );
    }
}
