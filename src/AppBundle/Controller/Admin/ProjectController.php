<?php

namespace AppBundle\Controller\Admin;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use AppBundle\Lib\Pipedrive\Pipedrive;
use AppBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/projects")
 *
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", name="admin_projects")
     * @Method("GET")
     * @Template("Admin/Project/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Project')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * @Route("/{id}/deleteconfirm", name="admin_projects_delete_confirm")
     * @Method("GET")
     * @Template("Admin/Project/delete-confirm.html.twig")
     *
     * @param int $id the Project.id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteConfirmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Project')->findOneBy(['id' => $id]);
        if ($entity == null) {
            $this->addFlash('alert alert-danger','Warning: Project not found');
            return $this->redirectToRoute('admin_projects');
        }

        $this->addFlash('alert alert-warning','Warning: The project you want to delete will be deleted with all dependencies such as Units, Files, Accessories, etc.');

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/delete", name="admin_projects_delete")
     * @Method("POST")
     * @Template("Admin/Project/index.html.twig")
     * @param int $id the Project.id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Project')->findOneBy(['id' => $id]);
        if (!$entity) {
            $this->addFlash('alert alert-warning','Warning: Project not found');
        } else {
            $em->remove($entity);
            try {
                $em->flush();
            } catch (DBALException $e) {
                $this->addFlash('alert alert-danger', 'errors.item_in_use');
            }
        }

        return $this->redirectToRoute('admin_projects');
    }

    /**
     * @Route("/sync", name="admin_projects_sync")
     * @Method("POST")
     * @Template("Admin/Project/index.html.twig")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function syncAction(Request $request) {
        $pipedriveSyncManager = $this->get('app.manager.pipedrive_sync');

        try {
            $filterOpenDeals = $request->request->get('sync_only_open_deals');
            $pipedriveSyncManager->syncAllProjects($filterOpenDeals);
        } catch (\Exception $e) {
            $this->addFlash('alert alert-danger','Error: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_projects');
    }
}
