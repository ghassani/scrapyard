<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ConfigurationType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @Route("/configuration")
 */
class ConfigurationController extends Controller
{
    const DEFAULT_GROUP = 'Store';
    
    /**
     * @Route("/", name="commerce_admin_configuration")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('commerce.admin.entity_manager');
        $configurationManager = $this->get('commerce.configuration');
        
        $currentGroup = $this->getRequest()->query->get('group', static::DEFAULT_GROUP);
        
        // load main level group names
        $groups = $em->getRepository('SplicedCommerceAdminBundle:Configuration')
          ->getConfigurationGroups();
        
        $currentGroupData = $em->getRepository('SplicedCommerceAdminBundle:Configuration')
          ->getConfigurationForGroup($currentGroup);
        
        $forms = array();
        foreach ($currentGroupData as $configData) {
            $childGroup = $configData->getChildGroup() ? $configData->getChildGroup() : 'Uncategorized';
            if(!isset($forms[$childGroup])){
                $forms[$childGroup] = $this->createFormBuilder();
            }
            
            $type = $configurationManager->getFieldType($configData->getType());
            $type->buildForm($configData, $forms[$childGroup]);
        }
        
        
        
        return array(
            'forms' => array_map(function(&$v){
                return $v->getForm()->createView();
            }, $forms),
            'groups' => $groups,
        );
    }
  
    
}
