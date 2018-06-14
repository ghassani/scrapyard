<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration\Type;

use Spliced\Component\Commerce\Model\ConfigurationInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

/**
 * EmailTemplateType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class EmailTemplateType extends TemplateType
{
    const DS = DIRECTORY_SEPARATOR;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'email_template';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getApplicationValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDatabaseValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(ConfigurationInterface $configData, FormBuilderInterface $form)
    {
        $form->add($configData->getFormSafeKey(), 'choice', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData->getValue()),
            'multiple' => false,
            'choices' => $this->getTemplates(),
            'required' => $configData->isRequired(),
            'empty_value' => $configData->isRequired() ? ' ' : null,
        ));
    }
    
    /**
     * getTemplates
     * 
     * Iterate over each bundle Resources/views directory 
     * to get a list of available templates for the user to
     * choose from.
     */
    private function getTemplates()
    {
        if( isset($this->templates)) {
            return $this->templates;
        }

        $finder = new Finder();
        $return = array('SplicedCommerceBundle' => array());
        
        $frontendBundle = false;
        $backendBundle = false;
        foreach ($this->kernel->getBundles() as $bundle) {
            if (in_array($bundle->getName(), $this->getExcludedBundles())) {
                continue;
            }
            
            if ($bundle->getName() == 'SplicedCommerceBundle') {
                $frontendBundle = $bundle->getPath();
            } elseif ($bundle->getName() == 'SplicedCommerceAdminBundle') {
                $backendBundle = $bundle->getPath();
            }
            
            $path = $bundle->getPath().static::DS.'Resources'.static::DS.'views'.static::DS.'Email'.static::DS;
            
            if (!file_exists($path) || ! is_dir($path)) {
                continue;
            }
            
            if(!isset($return[$bundle->getName()])){
                $return[$bundle->getName()] = array();
            }
            
            $iterator = $finder
            ->create()
            ->files()
            ->in($path);
            
            foreach ($iterator as $file) {
                $return[$bundle->getName()][$file->getRealpath()] = $bundle->getName().':Email/'.str_replace($path, '', $file->getRealpath());
            }
        }
        
        if (!$frontendBundle && $backendBundle) {
            $frontendBundle = $backendBundle.'/../CommerceAdminBundle/Resources/views/Email';
            
            if(!file_exists($frontendBundle) || !is_dir($frontendBundle)){
                unset($return['SplicedCommerceBundle']);
                continue;
            } else {
                $iterator = $finder
                ->create()
                ->files()
                ->in($frontendBundle);
                            
                foreach ($iterator as $file) {
                    $return['SplicedCommerceBundle'][$file->getRealpath()] = 'SplicedCommerceBundle'.':'.str_replace($path, '', $file->getRealpath());
                }
            }
        } else {
            throw new \RuntimeException('Could Not Find Frontend or Backend Bundles. Something went wrong.');
        }
        
        // store them in this service for future fetches
        $this->templates = $return;
        
        unset($return);
        unset($bundlePaths);
        unset($fileLocator);
        
        return $this->templates;
    }
    
    /**
     * getExcludedBundles
     * 
     * @return array
     */
    private function getExcludedBundles()
    {
        return array(
            'FrameworkBundle',
            'SecurityBundle',
            'TwigBundle',
            'SwiftmailerBundle',
            'DoctrineBundle',
            'DoctrineMongoDBBundle',
            'WebProfilerBundle',
            'SensioDistributionBundle',
            'KnpPaginatorBundle',
        );
    }
    

}
