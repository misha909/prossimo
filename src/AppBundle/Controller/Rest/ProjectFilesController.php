<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Project;
use AppBundle\Entity\File;
use AppBundle\Form\FileType;
use AppBundle\Lib\Pipedrive\Pipedrive;
use AppBundle\Model\FileCollection;
use finfo;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\RouteRedirectView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
 * Rest controller for files
 *
 * @package AppBundle\Controller
 */
class ProjectFilesController extends FOSRestController
{
    /**
     * List all projectFiles
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing files.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many files to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param int                   $project_id   the project id
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getFilesAction(Request $request, ParamFetcherInterface $paramFetcher, $project_id) // "get_projects_files" [GET] /api/projects/{project_id}/files
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);

        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $limit = 0 == $limit ? null : $limit; // when 0 - then  unlimited

        $files = $this->getDoctrine()->getManager()->getRepository('AppBundle:File')->findBy(["project" => $project], null, $limit, $offset);
        return new FileCollection($files, $offset, $limit);
    }

    /**
     * Get a single file.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\File",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the file is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="file")
     *
     * @param Request $request the request object
     * @param int     $project_id      the project id
     * @param int     $file_id         the file id
     *
     * @return array
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when file not exist
     */
    public function getFileAction(Request $request, $project_id, $file_id) // "get_projects_file" [GET] /api/projects/{project_id}/files/{file_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $file = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(["project" => $project, "id" => $file_id]);
        if (!$file instanceof File) {
            throw $this->createNotFoundException("File does not exist.");
        }

        return ['project_file' => $file];
    }

    /**
     * Creates a new file from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\FileType",
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
    public function postFilesAction(Request $request, $project_id) // "post_projects_files"     [POST] /api/projects/{project_id}/files
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $file = new File();
        $form = $this->createForm(new FileType(), $file);
        $form->submit($request);
        if ($form->isValid()) {
            $project->addFile($file);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->routeRedirectView('get_projects_file', array('project_id' => $project_id,'file_id' => $file->getId()));
        }
        return array(
            'form' => $form
        );
    }

    /**
     * Update existing file from the submitted data or create a new file at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\FileType",
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
     * @param int     $file_id         the file id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when project not exist
     * @throws NotFoundHttpException when file not exist
     */
    public function putFileAction(Request $request, $project_id, $file_id) // "put_projects_file"      [PUT] /api/projects/{project_id}/files/{file_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $file = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(["project" => $project, "id" => $file_id]);

        if (!$file instanceof File) {
            $file = new File();
            $file->setId($file_id);
            $file->setProject($project);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new FileType($project), $file);
        $form->submit($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();
            return $this->routeRedirectView('get_projects_file', array('project_id' => $project_id,'file_id' => $file->getId()), $statusCode);
        }
        return $form;
    }


    /**
     * Removes a file.
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
     * @param int     $file_id      the file id
     *
     * @return View
     *
     * @throws NotFoundHttpException when project not exist
     */
    public function deleteFilesAction(Request $request, $project_id, $file_id) // "delete_projects_file"   [DELETE] /api/projects/{project_id}/files/{file_id}
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $file = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(["project" => $project, "id" => $file_id]);

        if ($file instanceof File) {
            $em = $this->getDoctrine()->getManager();
            $project->removeFile($file);
            $em->persist($project);
            $em->remove($file);
            $em->flush();
        }
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_projects_files', array("project_id" => $project->getId()), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a file.
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
     * @param int     $file_id      the file id
     *
     * @return View
     */
    public function removeFilesAction(Request $request, $project_id, $file_id) // "remove_projects_file"   [GET] /api/projects/{project_id}/files/{file_id}/remove
    {
        return $this->deleteFilesAction($request, $project_id, $file_id);
    }

    /**
     * Download a project file.
     *
     * @Method({"GET"})
     * @Route("/projects/{project_id}/files/{file_id}/download", name="rest_project_files_download")
     *
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
     * @param int     $file_id      the file id
     *
     * @return File
     */
    public function downloadFileAction(Request $request, $project_id, $file_id) { // "download_projects_file" [GET] /api/projects/{project_id}/files/{file_id}/download
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($project_id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $file = $this->getDoctrine()->getRepository('AppBundle:File')->findOneBy(["project" => $project, "id" => $file_id]);

        if (!$file instanceof File) {
            throw new NotFoundHttpException('File not found');
        }

        // File founded and it is a File (File Metadata)
        $fileBinary = null; // link to real file
        // TODO: Check file in cache and return it from cache
        // Getting file from PipeDrive
        $downloadUrl = $this->getDowloadFileUrlFile($file->getPipedriveId());

        $content = file_get_contents($downloadUrl);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($content);
        // TODO: Store to localstorage (cache) update fileinfo with MIME and so on


        $filename = $file->getName();
        $headers = array(
            'Content-Type' => $mime,
            'Content-Disposition' => "attachment; filename=" . urlencode($filename),
        );

        return new Response($content, 200, $headers);
    }

    private function getDowloadFileUrlFile($pipedriveId) {
        $settingsManager = $this->get('app.manager.app_settings');
        $pipedriveApiToken = $settingsManager->getPipedriveApiToken();
        $api = new Pipedrive($pipedriveApiToken);
        $dealFile = $api->files()->getRealDownloadUrlById($pipedriveId);
        return $dealFile;
    }
}
