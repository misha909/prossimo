<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Project;
use AppBundle\Entity\Unit;
use AppBundle\Form\UnitType;
use AppBundle\Model\UnitCollection;
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
 * Rest controller for unit
 *
 * @package AppBundle\Controller
 */
class ProjectUnitsController extends FOSRestController
{
    /**
     * List all units
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing units.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many units to return.")
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
    public function getUnitsAction(Request $request, ParamFetcherInterface $paramFetcher, $project_id) // "get_projects_units" [GET] /api/projects/{project_id}/units
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $limit = 0 == $limit ? null : $limit; // when 0 - then  unlimited

        $units = $this->getDoctrine()->getManager()->getRepository('AppBundle:Unit')->findBy(["project" => $project], null, $limit, $offset);
        return new UnitCollection($units, $offset, $limit);
    }

    /**
     * Get a single unit.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Unit",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the unit is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="unit")
     *
     * @param Request $request the request object
     * @param int     $project_id   the project id
     * @param int     $unit_id    the unit_id
     *
     * @return array
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when unit not exist
     */
    public function getUnitAction(Request $request, $project_id, $unit_id) // "get_projects_unit" [GET] /api/projects/{project_id}/unit/{unit_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $unit = $this->getDoctrine()->getRepository('AppBundle:Unit')->findOneBy(["project" => $project, "id" => $unit_id]);
        if (!$unit instanceof Unit) {
            throw $this->createNotFoundException("Unit does not exist.");
        }

        return ['project_unit' => $unit];
    }

    /**
     * Creates a new unit from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\UnitType",
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
    public function postUnitsAction(Request $request, $project_id) // "post_projects_unit"     [POST] /api/projects/{project_id}/units
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $unit = new Unit();
        $form = $this->createForm(new UnitType(), $unit);
        $form->submit($request);
        if ($form->isValid()) {
            $project->addUnit($unit);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->routeRedirectView('get_projects_unit', array('project_id' => $project_id,'unit_id' => $unit->getId()));
        }
        return array(
            'form' => $form
        );
    }

    /**
     * Update existing unit from the submitted data or create a new unit at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\UnitType",
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
     * @param int     $unit_id      the unit id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when unit not exist
     */
    public function putUnitAction(Request $request, $project_id, $unit_id) // "put_projects_unit"      [PUT] /api/projects/{project_id}/units/{unit_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $unit = $this->getDoctrine()->getRepository('AppBundle:Unit')->findOneBy(["project" => $project, "id" => $unit_id]);

        if (!$unit instanceof Unit) {
            $unit = new Unit();
            $unit->setId($unit_id);
            $unit->setProject($project);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new UnitType(), $unit);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unit);
            $em->flush();
            return $this->routeRedirectView('get_projects_unit', array('project_id' => $project_id,'unit_id' => $unit->getId()), $statusCode);
        }
        return $form;
    }


    /**
     * Removes a unit.
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
     * @param int     $unit_id    the unit id
     *
     * @return View
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function deleteUnitsAction(Request $request, $project_id, $unit_id) // "delete_projects_window"   [DELETE] /api/projects/{project_id}/units/{unit_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $unit = $this->getDoctrine()->getRepository('AppBundle:Unit')->findOneBy(["project" => $project, "id" => $unit_id]);

        if ($unit instanceof Unit) {
            $em = $this->getDoctrine()->getManager();
            $project->removeUnit($unit);
            $em->persist($project);
            $em->remove($unit);
            $em->flush();
        }
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_projects_units', array("project_id" => $project->getId()), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a unit.
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
     * @param int     $unit_id    the unit id
     *
     * @return View
     */
    public function removeUnitsAction(Request $request, $project_id, $unit_id) // "remove_projects_unit"   [GET] /api/projects/{project_id}/units/{unit_id}/remove
    {
        return $this->deleteUnitsAction($request, $project_id, $unit_id);
    }
}
