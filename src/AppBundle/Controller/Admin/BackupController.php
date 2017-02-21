<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\AppSetting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/backup")
 */
class BackupController extends Controller
{
    public static $defaultBackupSettings = [
        ['system_name' => 'backup_database_user', 'display_name' => 'Database backup user', 'group_name' => 'backup', 'value' => 'pguser'],
        ['system_name' => 'backup_database_pass', 'display_name' => 'Database backup user password', 'group_name' => 'backup', 'value' => 'pgpassword'],
        ['system_name' => 'backup_database_host', 'display_name' => 'Database host', 'group_name' => 'backup', 'value' => 'localhost'],
        ['system_name' => 'backup_database_name', 'display_name' => 'Database name', 'group_name' => 'backup', 'value' => 'prossimo'],
        ['system_name' => 'backup_catalog_path', 'display_name' => 'Backup files catalog path', 'group_name' => 'backup', 'value' => '/var/backup'],
        ['system_name' => 'backup_filename_format', 'display_name' => 'Backup filename format', 'group_name' => 'backup', 'value' => '{{dbname}}_{{date}}.sql.backup']
    ];

    /**
     * @Route("/", name="admin_backup")
     * @Method("GET")
     * @Template("Admin/Backup/index.html.twig")
     */
    public function indexAction()
    {
        $settings = $this->getBackupSettings();
        $backupCatalog = $settings['backup_catalog_path'];
        $backupFilenameFormat = $settings['backup_filename_format'];

        // TODO: Add button to send backup file to GDrive using API

        $backupFiles = $this->getAllBackupFiles($backupCatalog, $backupFilenameFormat);

        return [
            'backup_catalog_path' => $backupCatalog,
            'backup_files' => $backupFiles
        ];
    }

    /**
     * @Route("/process", name="admin_backup_process")
     * @Method("GET")
     * @Template("Admin/Backup/process.html.twig")
     */
    public function backupProcessAction() {
        // TODO: Execute backup command and output result as Flash
        $settings = $this->getBackupSettings();
        $result = $this->executeSystemBackupCommand(
            $settings['backup_database_user'],
            $settings['backup_database_pass'],
            $settings['backup_database_host'],
            $settings['backup_database_name'],
            $settings['backup_catalog_path'],
            $settings['backup_filename_format']
        );

        return $this->redirectToRoute('admin_backup');
    }

    /**
     * @Route("/settings", name="admin_backup_settings")
     * @Method("GET")
     * @Template("Admin/Backup/settings.html.twig")
     */
    public function backupSettingsAction() {
        $settingsData = $this->getBackupSettings();
        $form = $this->createBackupSettingsForm($settingsData);

        // TODO: Save new settings if changed

        return ['form' => $form->createView()];
    }

    private function getBackupSettings() {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:AppSetting');

        $settingsDataArray = [];
        foreach (self::$defaultBackupSettings as $setting) {
            $value = $repo->findOneBy(['system_name' => $setting['system_name'], 'group_name' => $setting['group_name']]);
            if (!$value instanceof AppSetting) {
                $value = $repo->setAppSetting(
                    $setting['system_name'],
                    $setting['value'],
                    $setting['display_name'],
                    $setting['group_name']
                );
            }
            $settingsDataArray[$value->getSystemName()] = $value->getValue();
        }

        return $settingsDataArray;
    }

    private function createBackupSettingsForm($settingsData) {
        $form = $this->createFormBuilder($settingsData)
            ->add('backup_database_user', 'text', ['label' => 'Database backup user'])
            ->add('backup_database_pass', 'text', ['label' => 'Database backup user password'])
            ->add('backup_database_host', 'text', ['label' => 'Database host'])
            ->add('backup_database_name', 'text', ['label' => 'Database name'])
            ->add('backup_catalog_path', 'text', ['label' => 'Backup catalog path'])
            ->add('backup_filename_format', 'text', ['label' => 'Backup filename'])
            ->add('save', 'submit', array('label' => 'Save backup settings'))
            ->getForm();
        return $form;
    }

    private function getAllBackupFiles($backupCatalogPath, $backupFilenameFormat) {
        // TODO: Parse FilenameFormat and use it
        $files = array();
        foreach (glob($backupCatalogPath . "prossimo_*.sql.backup") as $file) {
            $fileInfo = pathinfo($file);
            $fileInfo['filesize'] = filesize($file);
            $files[$file] = $fileInfo;
        }
        krsort($files);
        return $files;
    }

    private function executeSystemBackupCommand($dbuser, $dbpass, $dbhost, $dbname, $backupCatalogPath, $backupFilenameFormat) {
        // TODO: Parse FilenameFormat and use it
        $backupDatetime = new \DateTime();
        $backupFilename = $dbname . '_' . $backupDatetime->format('YmdHis') .'.sql.backup';
        $backupPath = $backupCatalogPath . $backupFilename;

        $commandParts = [];
        $commandParts[0] = "export PGUSER=" . $dbuser;
        $commandParts[1] = "export PGPASSWORD=" . $dbpass;
        $commandParts[2] = "pg_dump -h " . $dbhost . " -d " . $dbname . " -Co > " . $backupPath;
        $commandParts[3] = "unset PGPASSWORD";
        $commandParts[4] = "unset PGUSER";

        $command = implode(" && ", $commandParts);
        $output = '';
        $result = exec($command, $output);
        // $result = exec("export PGPASSWORD=mypassword && export PGUSER=myuser && pg_dump -h yourremotehost -d db_name -Co > /tmp/db_name_backup.sql && unset PGPASSWORD && unset PGUSER");
        return $result;
    }
}
