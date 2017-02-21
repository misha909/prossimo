<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Accessory;
use AppBundle\Entity\Project;
use AppBundle\Form\AccessoryType;
use AppBundle\Model\AccessoryCollection;
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
 * Rest controller for window
 *
 * @package AppBundle\Controller
 */
class ProjectAccessoriesController extends FOSRestController
{
    /**
     * List all accessories
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing accessories.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many accessories to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param int                   $project_id   the project id
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function getAccessoriesAction(Request $request, ParamFetcherInterface $paramFetcher, $project_id) // "get_projects_accessories" [GET] /api/projects/{project_id}/accessories
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $limit = 0 == $limit ? null : $limit; // when 0 - then  unlimited
        $accessories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Accessory')->findBy(["project" => $project], null, $limit, $offset);
        return new AccessoryCollection($accessories, $offset, $limit);
    }

    /**
     * Get a single accessory.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Accessory",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the accessory is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="accessory")
     *
     * @param Request $request the request object
     * @param int     $project_id   the project id
     * @param int     $accessory_id    the accessory_id
     *
     * @return array
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when accessory not exist
     */
    public function getAccessoryAction(Request $request, $project_id, $accessory_id) // "get_projects_accessory" [GET] /api/projects/{project_id}/accessories/{accessory_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $accessory = $this->getDoctrine()->getRepository('AppBundle:Accessory')->findOneBy(["project" => $project, "id" => $accessory_id]);
        if (!$accessory instanceof Accessory) {
            throw $this->createNotFoundException("Accessory does not exist.");
        }

        return ['project_accessory' => $accessory];
    }

    /**
     * Creates a new $accessory from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\AccessoryType",
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
     * @param int     $project_id   the project id
     * @param Request $request      the request object
     *
     * @return FormTypeInterface[]|View
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function postAccessoriesAction(Request $request, $project_id) // "post_projects_accessory"     [POST] /api/projects/{project_id}/accessories
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $accessory = new Accessory();
        $form = $this->createForm(new AccessoryType(), $accessory);
        $form->submit($request);
        if ($form->isValid()) {
            $project->addAccessory($accessory);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->routeRedirectView('get_projects_accessory', array('project_id' => $project_id,'accessory_id' => $accessory->getId()));
        }
        return array(
            'form' => $form
        );
    }

    /**
     * Update existing accessory from the submitted data or create a new accessory at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\AccessoryType",
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
     * @param int     $accessory_id      the accessory id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when accessory not exist
     */
    public function putAccessoryAction(Request $request, $project_id, $accessory_id) // "put_projects_accessory"      [PUT] /api/projects/{project_id}/accessories/{accessory_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $accessory = $this->getDoctrine()->getRepository('AppBundle:Accessory')->findOneBy(["project" => $project, "id" => $accessory_id]);

        if (!$accessory instanceof Accessory) {
            $accessory = new Accessory();
            $accessory->setId($accessory_id);
            $accessory->setProject($project);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new AccessoryType(), $accessory);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accessory);
            $em->flush();
            return $this->routeRedirectView('get_projects_accessory', array('project_id' => $project_id,'accessory_id' => $accessory->getId()), $statusCode);
        }
        return $form;
    }


    /**
     * Removes an accessory.
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
     * @param int     $accessory_id    the accessory id
     *
     * @return View
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function deleteAccessoriesAction(Request $request, $project_id, $accessory_id) // "delete_projects_accessory"   [DELETE] /api/projects/{project_id}/accessories/{accessory_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $accessory = $this->getDoctrine()->getRepository('AppBundle:Accessory')->findOneBy(["project" => $project, "id" => $accessory_id]);

        if ($accessory instanceof Accessory) {
            $em = $this->getDoctrine()->getManager();
            $project->removeAccessory($accessory);
            $em->persist($project);
            $em->remove($accessory);
            $em->flush();
        }
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_projects_accessories', array("project_id" => $project->getId()), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a accessory.
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
     * @param int     $accessory_id    the accessory id
     *
     * @return View
     */
    public function removeAccessoriesAction(Request $request, $project_id, $accessory_id) // "remove_projects_accessories"   [GET] /api/projects/{project_id}/accessories/{accessory_id}/remove
    {
        return $this->deleteAccessoriesAction($request, $project_id, $accessory_id);
    }
}
