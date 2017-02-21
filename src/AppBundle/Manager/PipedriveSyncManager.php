<?php

namespace AppBundle\Manager;

use AppBundle\Entity\File;
use AppBundle\Entity\Project;
use AppBundle\Lib\Pipedrive\Pipedrive;
use Doctrine\ORM\EntityManager;

class PipedriveSyncManager
{
    protected $em;
    protected $asm;
    protected $pipedriveApi;

    /**
     * @param EntityManager $em
     * @param AppSettingsManager $asm
     */
    public function __construct(EntityManager $em, AppSettingsManager $asm)
    {
        $this->em = $em;
        $this->asm = $asm;
        $pipedriveApiToken = $this->asm->getPipedriveApiToken();
        $this->pipedriveApi = new Pipedrive($pipedriveApiToken);
    }

    public function syncAllProjects($syncOnlyOpenDeals = false, $isSyncFilesRequired = true) {
        $deals = null;

        if ($syncOnlyOpenDeals) {
            $deals = $this->pipedriveApi->deals()->getAllFiltered(1); // filter_id = 1  OpenedDeals
        } else {
            $deals = $this->pipedriveApi->deals()->getAll();
        }

        if ($deals && (true == $deals['success'])) {
            $dealsData = $deals['data'];
            if (is_array($dealsData)) {
                $projects = $this->em->getRepository('AppBundle:Project')->findAll();

                foreach ($dealsData as $deal) {
                    $currentProject = null;
                    foreach ($projects as $project) {
                        if ($deal['id'] == $project->getPipedriveId()) {
                            $currentProject = $project;
                            // Update project
                            $this->updateProjectFromPipedriveDeal($project, $deal);
                        }
                    }
                    if (!$currentProject) {
                        // create new project
                        $currentProject = $this->createProjectFromPipedriveDeal($deal);
                    }
                    // Sync Files if required
                    if ($isSyncFilesRequired) {
                        $this->syncAllProjectFiles($currentProject->getId());
                    }
                    // TODO: Sync Accessories if required
                    // TODO: Sync Units if required
                }
            } else {
                // $this->addFlash('alert alert-info','Info: There are no Deals founded. Nothing to sync.');
                throw new \Exception('Info: There are no Deals founded. Nothing to sync.', 1);
            }
        } else {
            // TODO: GET $deals['error'] here and pass it to exception message
            throw new \Exception('Error:');
        }
    }

    public function syncProject($id) {
        throw new \Exception('Not implemented method: ' . __METHOD__);
    }

    public function syncAllProjectFiles($projectId) {
        $project = $this->em->getRepository('AppBundle:Project')->findOneBy(["id" => $projectId]);
        $params = [];
        $params['id'] = $project->getPipedriveId();
        //$params['start'] = 0;
        //$params['limit'] = 0;
        //$params['include_deleted_files'] = 0; // [0|1]
        //$params['sort'] = 0;

        // Get all files from pipedrive for defined DealID (Project.PipedriveID)
        $filesRequest = $this->pipedriveApi->deals()->files($params);

        if ($filesRequest && (true == $filesRequest['success'])) {
            $filesData = $filesRequest['data'];
            if (is_array($filesData)) {
                foreach ($filesData as $dealFile) {
                    $projectFiles = $this->em->getRepository('AppBundle:File')->findBy(["project" => $projectId]);

                    $currentProjectFile = null;
                    foreach ($projectFiles as $projectFile) {
                        if ($dealFile['id'] == $projectFile->getPipedriveId()) {
                            $currentProjectFile = $projectFile;
                            // Update projectFile
                            $this->updateProjectFileFromPipedriveDealFile($projectFile, $dealFile);
                        }
                    }
                    if (!$currentProjectFile) {
                        // create new projectFile
                        $newProjectFile = $this->createProjectFileFromPipedriveDealFile($project, $dealFile);
                        $entities[] = $newProjectFile;
                    }
                }
            } else {
                // $this->addFlash('alert alert-info','Info: There are no Files founded for defined project "' . $project . '". Nothing to sync.');
                throw new \Exception('Info: There are no Files founded for defined project "' . $project . '". Nothing to sync.', 1);
            }
        } else {
            // TODO: GET $deals['error'] here and pass it to exception message
            throw new \Exception('Error:');
        }
    }

    public function syncFile($id) {
        throw new \Exception('Not implemented method: ' . __METHOD__);
    }


    private function createProjectFromPipedriveDeal($deal) {
        $pipedriveCustomFieldProjectAddressKey = $this->asm->getPipedriveCustomFieldProjectAddressKey();

        $project = new Project();
        $project->setPipedriveId($deal['id']);
        $project->setClientName($deal['person_name']);
        $project->setClientCompanyName($deal['org_name']);
        if (array_key_exists('person_id',$deal) && ($deal['person_id'] != null)) {
            $phone = (array_key_exists('phone', $deal['person_id'])) ? $deal['person_id']['phone'][0]['value'] : '';
            $email = (array_key_exists('email', $deal['person_id'])) ? $deal['person_id']['email'][0]['value'] : '';
        } else {
            $phone = null;
            $email = null;
        }
        $project->setClientPhone($phone);
        $project->setClientEmail($email);
        $org = $this->getPipedriveOrganisationAddress($deal['org_id']['value']);
        $project->setClientAddress($org);
        $project->setProjectName($deal['title']);
        $project->setProjectAddress($deal[$pipedriveCustomFieldProjectAddressKey]);
        $project->setSyncDatetime(new \DateTime("now"));

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    private function updateProjectFromPipedriveDeal(Project $project, $deal) {
        $pipedriveCustomFieldProjectAddressKey = $this->asm->getPipedriveCustomFieldProjectAddressKey();

        $project->setPipedriveId($deal['id']);
        $project->setClientName($deal['person_name']);
        $project->setClientCompanyName($deal['org_name']);
        if (array_key_exists('person_id',$deal) && ($deal['person_id'] != null)) {
            $phone = (array_key_exists('phone', $deal['person_id'])) ? $deal['person_id']['phone'][0]['value'] : '';
            $email = (array_key_exists('email', $deal['person_id'])) ? $deal['person_id']['email'][0]['value'] : '';
        } else {
            $phone = null;
            $email = null;
        }
        $project->setClientPhone($phone);
        $project->setClientEmail($email);
        $org = $this->getPipedriveOrganisationAddress($deal['org_id']['value']);
        $project->setClientAddress($org);
        $project->setProjectName($deal['title']);
        $project->setProjectAddress($deal[$pipedriveCustomFieldProjectAddressKey]);
        $project->setSyncDatetime(new \DateTime("now"));

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    private function getPipedriveOrganisationAddress($orgId) {
        if (is_int($orgId) && ($orgId > 0)) {
            $orgResponse = $this->pipedriveApi->organizations()->getById($orgId);
            if ($orgResponse && (true == $orgResponse['success'])) {
                return $orgResponse['data']['address'];
            }
        }
        return '';
    }

    private function createProjectFileFromPipedriveDealFile($project, $dealFile) {
        $file = new File();
        $file->setProject($project);
        $file->setPipedriveId($dealFile['id']);
        $file->setName($dealFile['name']);
        $file->setType($dealFile['file_type']);
        $file->setUrl($dealFile['url']);

        $file->setSyncDatetime(new \DateTime("now"));

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }

    private function updateProjectFileFromPipedriveDealFile(File $file, $dealFile) {
        $file->setPipedriveId($dealFile['id']);
        $file->setName($dealFile['name']);
        $file->setType($dealFile['file_type']);
        $file->setUrl($dealFile['url']);

        $file->setSyncDatetime(new \DateTime("now"));

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }
}