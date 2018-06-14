<?php

namespace Spliced\Bundle\CommerceAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SplicedCommerceAdminExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        //$existingBundles = 
        
        $isCombinedEnviornment = false;
        
        if(isset($existingBundles['SplicedCommerceBundle'])){
            $isCombinedEnviornment = true;
        }
         
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        
        $loader->load('security.xml');
        $loader->load('configuration.xml');
        $loader->load('events.xml');
        $loader->load('soap.xml');
    

        if(!$isCombinedEnviornment){
            
            $container->setAlias('commerce.configuration', 'commerce.admin.configuration');
            
            $loader->load('services.xml');
            $loader->load('twig.xml');
            
            $frontLoader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../CommerceBundle/Resources/config'));
            $frontLoader->load('payment.xml');
            $frontLoader->load('shipping.xml');
            $frontLoader->load('product_types.xml');
            $frontLoader->load('configuration_field_types.xml');
            
            $container->setParameter('commerce.checkout_notifiers', array());
        } 
        
        $container->setAlias('commerce.admin.entity_manager', $config['entity_manager']);
        $container->setAlias('commerce.admin.document_manager', $config['document_manager']);
        
        $container->setParameter('commerce.is_combined_enviornment', $isCombinedEnviornment);                
    } 
}
