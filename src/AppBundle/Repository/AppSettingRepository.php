<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\AppSetting;

class AppSettingRepository extends EntityRepository
{
    /**
     * Set AppSetting
     *
     * @param \AppBundle\Repository\string $systemName
     * @param \AppBundle\Repository\string $value
     * @param \AppBundle\Repository\string|null $displayName
     * @param \AppBundle\Repository\string|null $groupName
     *
     * @return \AppBundle\Entity\AppSetting|null|object
     */
    public function setAppSetting($systemName, $value, $displayName = null, $groupName = null) {
        // find existing setting
        $setting = $this->findOneBy(['system_name' => $systemName, 'group_name' => $groupName]);
        if ($setting instanceof AppSetting) {
            $setting->setValue($value);
        } else {
            $displayName = null == $displayName ? $systemName : $displayName;
            $setting = new AppSetting();
            $setting->setSystemName($systemName);
            $setting->setValue($value);
            $setting->setDisplayName($displayName);
            $setting->setGroupName($groupName);
        }

        $this->_em->persist($setting);
        $this->_em->flush($setting);

        return $setting;
    }

    /**
     * Get AppSetting or create it
     *
     * @param \AppBundle\Repository\string $systemName
     * @param \AppBundle\Repository\string|null $groupName
     * @return \AppBundle\Entity\AppSetting|null
     */
    public function getAppSettingValue(string $systemName, string $groupName = null) {
        return $this->findOneBy(['system_name' => $systemName, 'group_name' => $groupName]);
    }
}