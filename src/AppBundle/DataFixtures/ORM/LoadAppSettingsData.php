<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AppSetting;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAppSettingsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        //$system_name, $display_name, $value
        $data = [
            ['pipedrive_token', 'Pipedrive API. Token', 'setYourTokenHere'],
            ['pipedrive_address_field_token', 'Pipedrive API. Custom address field token', 'setYourTokenHere'],
        ];

        foreach ($data as $key => $item) {
            $e = new AppSetting();
            $e->setSystemName($item[0]);
            $e->setDisplayName($item[1]);
            $e->setValue($item[2]);

            $om->persist($e);

            $this->addReference('app_setting_'.$key, $e);
        }

        $om->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
