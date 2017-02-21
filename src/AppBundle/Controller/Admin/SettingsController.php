<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\AppSettingsCollectionType;
use AppBundle\Model\AppSettingsCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin\Settings controller.
 *
 * @Route("/admin")
 *
 */
class SettingsController extends Controller
{
    /**
     * @Route("/settings", name="admin_settings")
     * @Method({"GET","POST"})
     * @Template(":Admin/Settings:index.html.twig")
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:AppSetting')->findAll();

        $appSettings = new AppSettingsCollection($entities);
        $form = $this->createForm(new AppSettingsCollectionType(), $appSettings);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $appSettingsCollection = $form->getData();

            foreach ($appSettingsCollection->getAppSettings() as $setting) {
                $em->persist($setting);
            }
            $em->flush();

            $this->addFlash('alert alert-success','Application settings saved!');

            return $this->redirect($this->generateUrl('admin_settings'));
        }

        return [ 'form' => $form->createView()];
    }
}
