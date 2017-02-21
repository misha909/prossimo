<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\FillingType;
use AppBundle\Form\FillingTypeType;
use AppBundle\Model\FillingTypeCollection;
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
 * Rest controller for profiles
 *
 * @package AppBundle\Controller
 */
class FillingTypesController extends FOSRestController
{
    /**
     * List all FillingTypes
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing resources.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many resources to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getFillingtypesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $limit = 0 == $limit ? null : $limit; // when 0 - then  unlimited
        $fillingTypes = $this->getDoctrine()->getManager()->getRepository('AppBundle:FillingType')->findBy([], null, $limit, $offset);
        return new FillingTypeCollection($fillingTypes, $offset, $limit);
    }

    /**
     * Get a single FillingType
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\FillingType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the fillingType is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="fillingType")
     *
     * @param Request $request the request object
     * @param int     $filling_type_id      The FillingType id
     *
     * @return array
     *
     * @throws NotFoundHttpException when fillingType not exist
     */
    public function getFillingtypeAction(Request $request, $filling_type_id)
    {
        $fillingType = $this->getDoctrine()->getRepository('AppBundle:FillingType')->find($filling_type_id);
        if (!$fillingType instanceof FillingType) {
            throw new NotFoundHttpException('FillingType not found');
        }

        return ['filling_type' => $fillingType];
    }


    /**
     * Creates a new FillingType from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\FillingTypeType",
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
    public function postFillingtypesAction(Request $request)
    {
        $fillingType = new FillingType();
        $form = $this->createForm(new FillingTypeType(), $fillingType);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fillingType);
            $em->flush();
            return $this->routeRedirectView('get_filling_type', array('filling_type_id' => $fillingType->getId()));
        }
        return array(
            'form' => $form
        );
    }

    /**
     * Update existing FillingType from the submitted data or create a new FillingType at a specific location (id)
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\FillingTypeType",
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
     * @param int     $filling_type_id      The FillingType id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when fillingType not exist
     */
    public function putFillingtypeAction(Request $request, $filling_type_id)
    {
        $fillingType = $this->getDoctrine()->getRepository('AppBundle:FillingType')->find($filling_type_id);

        if (!$fillingType instanceof FillingType) {
            $fillingType = new FillingType();
            $fillingType->setId($filling_type_id);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new FillingTypeType(), $fillingType);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fillingType);
            $em->flush();
            return $this->routeRedirectView('get_filling_type', array('filling_type_id' => $fillingType->getId()), $statusCode);
        }
        return $form;
    }

    /**
     * Removes a FillingType
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request      the request object
     * @param int     $filling_type_id           The FillingType id
     *
     * @return View
     */
    public function deleteFillingtypesAction(Request $request, $filling_type_id)
    {
        $fillingType = $this->getDoctrine()->getRepository('AppBundle:FillingType')->find($filling_type_id);

        if ($fillingType instanceof FillingType) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fillingType);
            $em->flush();
        }
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_filling_types', array(), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a FillingType
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request      The request object
     * @param int     $filling_type_id           The FillingType id
     *
     * @return View
     */
    public function removeFillingtypesAction(Request $request, $filling_type_id)
    {
        return $this->deleteFillingTypesAction($request, $filling_type_id);
    }
}
