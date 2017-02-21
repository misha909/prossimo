<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Profile;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProfileData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        //$name, $unit_type, $frame_width, $mullion_width, $sash_frame_width, $sash_frame_overlap, $sash_mullion_overlap, $low_threshold, $threshold_width, $system
        $data = [
            ['Default Profile', null, 70, 92, 82, 34, 12, null, null, 'Gealan S9000'],
            ['Alternative Profile', 'Patio Door', 90, 112, 102, 36, 14, true, 20, 'Gealan S9000'],
        ];

        foreach ($data as $key => $item) {
            $e = new Profile();
            $e->setName($item[0]);
            $e->setUnitType($item[1]);
            $e->setFrameWidth($item[2]);
            $e->setMullionWidth($item[3]);
            $e->setSashFrameWidth($item[4]);
            $e->setSashFrameOverlap($item[5]);
            $e->setSashMullionOverlap($item[6]);
            $e->setLowThreshold($item[7]);
            $e->setThresholdWidth($item[8]);
            $e->setSystem($item[9]);

            $om->persist($e);

            $this->addReference('profile_'.$key, $e);
        }

        $om->flush();
    }

    public function getOrder()
    {
        return 25;
    }
}
