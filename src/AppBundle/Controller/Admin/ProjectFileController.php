<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Lib\Pipedrive\Pipedrive;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/projects/{project_id}/files")
 *
 */
class ProjectFileController extends Controller
{
    /**
     * @Route("/", name="admin_project_files")
     * @Method("GET")
     * @Template("Admin/File/index.html.twig")
     * @param $project_id
     * @return array
     */
    public function indexAction($project_id)
    {
        $em = $this->getDoctrine()->getManager();
        $parentEntity = $em->getRepository('AppBundle:Project')->find($project_id);
        $entities = $em->getRepository('AppBundle:File')->findBy(["project" => $project_id]);

        $pipedriveSyncManager = $this->get('app.manager.app_settings');

        return [
            'parent_entity' => $parentEntity,
            'entities' => $entities,
            'api_token' => $pipedriveSyncManager->getPipedriveApiToken()
        ];
    }

    /**
     * @Route("/sync", name="admin_project_files_sync")
     * @Method("POST")
     * @Template("Admin/Project/index.html.twig")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $project_id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function syncAction(Request $request, $project_id) {
        $pipedriveSyncManager = $this->get('app.manager.pipedrive_sync');
        try {
            $pipedriveSyncManager->syncAllProjectFiles($project_id);
        } catch (\Exception $e) {
            $this->addFlash('alert alert-danger','Error: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_project_files', ['project_id' => $project_id]);
    }
}
