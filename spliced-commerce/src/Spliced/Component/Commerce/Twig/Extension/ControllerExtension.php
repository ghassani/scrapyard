<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ControllerExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ControllerExtension extends \Twig_Extension
{

    /**
     * Constructor
     *
     * @param ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_category_list' => new \Twig_Function_Method($this, 'renderCategoryList'),
            'commerce_category_menu' => new \Twig_Function_Method($this, 'renderCategoryMenu'),
            'commerce_cart_block'    => new \Twig_Function_Method($this, 'renderCartBlock'),
            'commerce_breadcrumbs'   => new \Twig_Function_Method($this, 'renderBreadcrumbs'),
            'commerce_top_products'  => new \Twig_Function_Method($this, 'renderTopProducts'),
            'commerce_attribute_search_form'  => new \Twig_Function_Method($this, 'renderAttributeSearchForm'),
            'commerce_newsletter_register'  => new \Twig_Function_Method($this, 'renderNewsletterRegister'),
        );
    }

    /**
     * renderTopProducts
     *
     * @param string $template
     *
     * @return string - Rendered HTML
     */
    public function renderTopProducts($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template : 'SplicedCommerceBundle:Product/Block:collection.html.twig',
            $this->container->get('commerce.controller.product')->getFrontProductsAction()
        );
    }

    /**
     * renderBreadcrumbs
     *
     * @param string $template
     *
     * @return string - Rendered HTML
     */
    public function renderBreadcrumbs($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template : 'SplicedCommerceBundle:Breadcrumb:default.html.twig',
            $this->container->get('commerce.controller.breadcrumb')->defaultAction()
        );
    }

    /**
     * renderCartBlock
     *
     * @param string $template
     *
     * @return string - Rendered HTML
     */
    public function renderCartBlock($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template : 'SplicedCommerceBundle:Cart:Blocks/side_block.html.twig',
            $this->container->get('commerce.controller.cart')->sideBlockAction()
        );
    }

    /**
     * renderCategoryMenu
     *
     * @param string $template
     *
     * @return string - Rendered HTML
     */
    public function renderCategoryMenu($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template : 'SplicedCommerceBundle:Category/Blocks:menu.html.twig',
            $this->container->get('commerce.controller.category')->menuAction()
        );
    }

    /**
     * renderCategoryList
     *
     * @param string $template
     *
     * @return string - Rendered HTML
     */
    public function renderCategoryList($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template :'SplicedCommerceBundle:Category/Blocks:list.html.twig',
            $this->container->get('commerce.controller.category')->listAction()
        );
    }
    
    public function renderAttributeSearchForm($template = null)
    {
        return $this->container->get('templating')->render(
            $template ? $template :'SplicedCommerceBundle:Search/Form:attribute_filter_form.html.twig',
            $this->container->get('commerce.controller.search')->attributeFilterFormAction()
        );
    }
    
    public function renderNewsletterRegister($template = null){
        return $this->container->get('templating')->render(
                $template ? $template :'SplicedCommerceBundle:Newsletter:register_block.html.twig',
                $this->container->get('commerce.controller.newsletter')->registerBlockAction()
        ); 
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_controller';
    }

}
