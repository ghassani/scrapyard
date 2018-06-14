<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\Form\FormFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;
use Spliced\Component\Commerce\Model\ContentPageInterface;
use Spliced\Component\Commerce\Model\AffiliateInterface;
use Spliced\Component\Commerce\Model\ManufacturerInterface;


/**
 * AdminFormFactory
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AdminFormFactory
{
    
    protected $container;
    
    /**
     * Constructor
     * 
     * @param Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    /**
     * getFormFactory
     */
    protected function getFormFactory()
    {
        return $this->container->get('form.factory');
    }
    
    
    /**
     * createProductForm
     * 
     * @return FormInterface
     */
    public function createProductForm(ProductInterface $product = null, array $options = array())
    {
        if(is_null($product)){
            $product = $this->container->get('commerce.configuration')
              ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT);
        }
        
        if(!$product->getContent()->count() && $product->getId()){
            $defaultLocale = $this->container->get('commerce.configuration')->get('commerce.store.default_locale','en');            
            $defaultLocaleContent = $this->container->get('commerce.configuration')
             ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_CONTENT)
            ->setLanguage($defaultLocale);

            $product->addContent($defaultLocaleContent);
        }
        
        $formType = new Type\Admin\ProductType(
            $product,
            $this->container->get('commerce.configuration'),
            $this->container->get('commerce.product_type_manager'),
            $this->container->get('commerce.admin.entity_manager')
        );
    
        return $this->getFormFactory()->create($formType, $product, $options);
    }
    
    /**
     * createProductAttributeOptionForm
     *
     * @return FormInterface
     */
    public function createProductAttributeOptionForm(ProductAttributeOptionInterface $attributeOption = null, array $options = array())
    {
        if(is_null($attributeOption)){
            $attributeOption = $this->container->get('commerce.configuration')
             ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION);
        }

        $formType = new Type\Admin\ProductAttributeOptionType(
            $attributeOption,
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $attributeOption, $options);
    }
    
    /**
     * createProductAttributeOptionForm
     *
     * @return FormInterface
     */
    public function createProductSpecificationOptionForm(ProductSpecificationOptionInterface $specificationOption = null, array $options = array())
    {
        if(is_null($specificationOption)){
            $specificationOption = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION);
        }
    
        $formType = new Type\Admin\ProductSpecificationOptionType(
            $specificationOption,
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $specificationOption, $options);
    }
    
    /**
     * createCategoryForm
     *
     * @return FormInterface
     */
    public function createCategoryForm(CategoryInterface $category = null, array $options = array())
    {
        if(is_null($category)){
            $category = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CATEGORY);
        }
        
        
        
        $formType = new Type\Admin\CategoryType(
            $category,
            $this->container->get('commerce.configuration')
        );
        
        return $this->getFormFactory()->create($formType, $category, $options);
    }
    
    /**
     * createContentPageForm
     *
     * @return FormInterface
     */
    public function createContentPageForm(ContentPageInterface $page = null, array $options = array())
    {
        if(is_null($page)){
            $page = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CONTENT_PAGE);
        }
    
    
    
        $formType = new Type\Admin\ContentPageType(
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $page, $options);
    }
    
    /**
     * createAffiliateForm
     *
     * @return FormInterface
     */
    public function createAffiliateForm(AffiliateInterface $affiliate = null, array $options = array())
    {
        if(is_null($affiliate)){
            $affiliate = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_AFFILIATE);
        }

        $formType = new Type\Admin\AffiliateType(
            $affiliate,
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $affiliate, $options);
    }
    
    /**
     * createManufacturerForm
     *
     * @return FormInterface
     */
    public function createManufacturerForm(ManufacturerInterface $manufacturer = null, array $options = array())
    {
        if(is_null($manufacturer)){
            $manufacturer = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_MANUFACTURER);
        }
    
        $formType = new Type\Admin\ManufacturerType(
            $manufacturer,
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $manufacturer, $options);
    }
    

    /**
     * createRouteForm
     *
     * @return FormInterface
     */
    public function createRouteForm(RouteInterface $route = null, array $options = array())
    {
        if(is_null($route)){
            $route = $this->container->get('commerce.configuration')
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE);
        }
    
        $formType = new Type\Admin\RouteType(
            $route,
            $this->container->get('commerce.configuration')
        );
    
        return $this->getFormFactory()->create($formType, $route, $options);
    }
}