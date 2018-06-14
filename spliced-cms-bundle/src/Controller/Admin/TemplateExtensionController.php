<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Controller\Admin;

use Spliced\Bundle\CmsBundle\Entity\TemplateExtension;
use Spliced\Bundle\CmsBundle\Form\Type\TemplateExtensionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/template_extension")
 */
class TemplateExtensionController extends Controller
{
    /**
     * @Route("/", name="spliced_cms_admin_template_extension_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
    }
    /**
     * @Route("/{templateId}/new", name="spliced_cms_admin_template_extension_new")
     * @Template()
     */
    public function newAction(Request $request, $templateId)
    {
        
        try{
            $template = $this->getDoctrine()->getRepository('SplicedCmsBundle:Template')
                ->findOneById($templateId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Not Found'), 404);
        }
        
        $templateExtensions = $this->get('spliced_cms.template_manager')->getExtensions();
        
        if (!count($templateExtensions)) {
            return new JsonResponse(array('success' => true, 'message' => 'No Extensions Available'), 201);
        }

        $formType = new TemplateExtensionFormType($template, null);
        
        $formType->setExtensions($templateExtensions);
        
        $form = $this->createForm($formType, new TemplateExtension());
        
        if ($request->getMethod() == 'POST') {
            
            if ($form->submit($request) && $form->isValid()) {
                $templateExtension = $form->getData();
                $extension = $this->get('spliced_cms.template_manager')->getExtension($templateExtension->getExtensionKey());
                // rebuild the form with the settings form that the template extension
                // provides
                unset($formType);
                $formType = new TemplateExtensionFormType($template, $extension);
                $form = $this->createForm($formType, $templateExtension);
                return array(
                    'template' => $template,
                    'extension' => $extension,
                    'form' => $form->createView()
                );
            } else {

            }

        }

        return array(
            'template' => $template,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{templateId}/save", name="spliced_cms_admin_template_extension_save")
     * @Template()
     */
    public function saveAction(Request $request, $templateId)
    {
        if (!$request->request->has('extensionKey')) {
            return new JsonResponse(array('success' => false, 'message' => 'Extension Not Provided'), 404);
        }

        $extension = $this->get('spliced_cms.template_manager')->getExtension($request->request->get('extensionKey'));
        
        if (!$extension) {
            return new JsonResponse(array('success' => false, 'message' => 'Extension Not Found'), 404);
        }
        
        try{
            $template = $this->getDoctrine()->getRepository('SplicedCmsBundle:Template')
                ->findOneById($templateId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Not Found'), 404);
        }
        
        $formType = new TemplateExtensionFormType($template, $extension);
        
        $form = $this->createForm($formType, new TemplateExtension());
        
        if ($form->submit($request) && $form->isValid()) {
            $templateExtension = $form->getData();
            $templateExtension->setTemplate($template)
                ->setExtensionKey($extension->getKey());
            
            $this->get('doctrine.orm.entity_manager')->persist($templateExtension);
            
            $this->get('doctrine.orm.entity_manager')->flush();
            
            return array(
                'template'          => $template,
                'extension'         => $extension,
                'templateExtension' => $templateExtension,
                'form'              => $form->createView()
            );

        } else {

        }

        return array(
            'template'  => $template,
            'form'      => $form->createView(),
            'extension' => $extension,
        );
    }
    /**
     * @Route("/{templateId}/edit/{templateExtensionId}", name="spliced_cms_admin_template_extension_edit")
     * @Template()
     */
    public function editAction(Request $request, $templateId, $templateExtensionId)
    {
        try{
            $template = $this->getDoctrine()->getRepository('SplicedCmsBundle:Template')
                ->findOneById($templateId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Not Found'), 404);
        }

        try{
            $templateExtension = $this->getDoctrine()->getRepository('SplicedCmsBundle:TemplateExtension')
                ->findOneById($templateExtensionId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Extension Not Found'), 404);
        }

        $extension = $this->get('spliced_cms.template_manager')->getExtension($templateExtension->getExtensionKey());
        
        if (!$extension) {
            return new JsonResponse(array('success' => false, 'message' => 'Extension Not Found'), 404);
        }

        $form = $this->createForm(new TemplateExtensionFormType($template, $extension), $templateExtension);
        
        return array(
            'template'  => $template,
            'templateExtension'  => $templateExtension,
            'form'      => $form->createView(),
            'extension' => $extension,
        );
    }

    /**
     * @Route("/{templateId}/update/{templateExtensionId}", name="spliced_cms_admin_template_extension_update")
     * @Template()
     */
    public function updateAction(Request $request, $templateId, $templateExtensionId)
    {
        try{
            $template = $this->getDoctrine()->getRepository('SplicedCmsBundle:Template')
                ->findOneById($templateId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Not Found'), 404);
        }

        try{
            $templateExtension = $this->getDoctrine()->getRepository('SplicedCmsBundle:TemplateExtension')
                ->findOneById($templateExtensionId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Extension Not Found'), 404);
        }
        
        $extension = $this->get('spliced_cms.template_manager')->getExtension($templateExtension->getExtensionKey());
        
        if (!$extension) {
            return new JsonResponse(array('success' => false, 'message' => 'Extension Not Found'), 404);
        }
        
        $form = $this->createForm(new TemplateExtensionFormType($template, $extension), $templateExtension);
        
        if ($form->submit($request) && $form->isValid()) {
            $templateExtension = $form->getData();
            $this->getDoctrine()->getManager()->persist($templateExtension);
            $this->getDoctrine()->getManager()->flush();
        } else {
            return new JsonResponse(array('success' => false, 'message' => 'Error Validating Input'));
        }
        
        return array(
            'template'  => $template,
            'templateExtension'  => $templateExtension,
            'form'      => $form->createView(),
            'extension' => $extension,
        );
    }
    /**
     * @Route("/{templateId}/delete/{templateExtensionId}", name="spliced_cms_admin_template_extension_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $templateId, $templateExtensionId)
    {
        try{
            $template = $this->getDoctrine()->getRepository('SplicedCmsBundle:Template')
                ->findOneById($templateId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Not Found'), 404);
        }
        try{
            $templateExtension = $this->getDoctrine()->getRepository('SplicedCmsBundle:TemplateExtension')
                ->findOneById($templateExtensionId);
        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Template Extension Not Found'), 404);
        }
        $this->getDoctrine()->getManager()->remove($templateExtension);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array('success' => true, 'message' => 'Deleted Template Extension ID ' . $templateExtensionId ));
    }
}
