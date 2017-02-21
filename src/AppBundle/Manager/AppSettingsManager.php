<?php
namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class SettingsManager
 *
 * @package AppBundle\Manager
 */
class AppSettingsManager
{
    protected $em;
    protected $settingRepository;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->settingRepository = $this->em->getRepository('AppBundle:AppSetting');
    }

    private static $pipedriveApiToken = null;

    /**
     * Get PipedriveAPI Token value
     *
     * @return null|string
     */
    public function getPipedriveApiToken() {
        if (!self::$pipedriveApiToken) {
            $setting = $this->settingRepository->findOneBy(array('system_name' => 'pipedrive_token'));
            self::$pipedriveApiToken = $setting->getValue();
        }
        return self::$pipedriveApiToken;
    }

    private static $pipedriveApiCustomField_ProjectAddressKey = null;

    /**
     * Get PipedriveAPI CustomField ProjectAddress Key(Token)
     *
     * @return null|string
     */
    public function getPipedriveCustomFieldProjectAddressKey() {
        if (!self::$pipedriveApiCustomField_ProjectAddressKey) {
            $setting = $this->settingRepository->findOneBy(array('system_name' => 'pipedrive_address_field_token'));
            self::$pipedriveApiCustomField_ProjectAddressKey = $setting->getValue();
        }
        return self::$pipedriveApiCustomField_ProjectAddressKey;
    }


}