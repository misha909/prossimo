<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use AppBundle\Model\ProjectCollection;
use FOS\RestBundle\View\RouteRedirectView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

/**
 * Rest controller for projects
 *
 * @package AppBundle\Controller
 */
class ProjectsController extends FOSRestController
{
    /**
     * List all projects
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing projects.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many projects to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getProjectsAction(Request $request, ParamFetcherInterface $paramFetcher) // "get_projects" [GET] /api/projects
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $limit = 0 == $limit ? null : $limit; // when 0 - then  unlimited
        $projects = $this->getDoctrine()->getManager()->getRepository('AppBundle:Project')->findBy([], null, $limit, $offset);
        return new ProjectCollection($projects, $offset, $limit);
    }

    /**
     * Get a single project.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Project",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the project is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="project")
     *
     * @param Request $request the request object
     * @param int     $project_id      the project id
     *
     * @return array
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function getProjectAction(Request $request, $project_id) // "get_project" [GET] /api/projects/{project_id}
    {
        $model = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);

        if (!$model instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        return ['project' => $model];
    }

    /**
     * Creates a new project from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\ProjectType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "AppBundle:Project:new.html.twig",
     *   statusCode = Response::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function postProjectsAction(Request $request) // "post_projects"     [POST] /api/projects
    {
        $model = new Project();
        $form = $this->createForm(new ProjectType(), $model);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($model);
            $em->flush();
            return $this->routeRedirectView('get_project', array('project_id' => $model->getId()));
        }
        return array(
            'form' => $form
        );
    }

    /**
     * Update existing project from the submitted data or create a new project at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\ProjectType",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template="AppBundle:Project:edit.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $project_id      the project id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function putProjectAction(Request $request, $project_id) // "put_project"      [PUT] /api/projects/{project_id}
    {
        $model = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);

        if (!$model instanceof Project) {
            $model = new Project();
            $model->setId($project_id);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new ProjectType(), $model);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($model);
            $em->flush();
            return $this->routeRedirectView('get_projects', array('id' => $model->getId()), $statusCode);
        }
        return $form;
    }

    /**
     * Removes a project.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request      the request object
     * @param int     $project_id   the project id
     *
     * @return View
     */
    public function deleteProjectsAction(Request $request, $project_id) // "delete_project"   [DELETE] /api/projects/{project_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);

        if ($project instanceof Project) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_projects', array(), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a project.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request      the request object
     * @param int     $project_id   the project id
     *
     * @return View
     */
    public function removeProjectsAction(Request $request, $project_id) // "remove_project"   [GET] /api/projects/{project_id}/remove
    {
        return $this->deleteProjectsAction($request, $project_id);
    }
}
