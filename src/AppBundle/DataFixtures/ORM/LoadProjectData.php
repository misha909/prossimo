<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        //$pipedrive_id, $client_name, $client_company_name, $client_phone, $client_email, $client_address, $project_name, $project_address
        $data = [
            [null, 'Andy Huh', 'Fentrend', '917.468.0506', 'ben@prossimo.us','98 4th Street Suite 213 Brooklyn, NY 11231', 'Italian Market', '827 Carpenter Lane Philadelphia, PA'],
        ];

        foreach ($data as $key => $item) {
            $p = new Project();
            $p->setPipedriveId($item[0]);
            $p->setClientName($item[1]);
            $p->setClientCompanyName($item[2]);
            $p->setClientPhone($item[3]);
            $p->setClientEmail($item[4]);
            $p->setClientAddress($item[5]);
            $p->setProjectName($item[6]);
            $p->setProjectAddress($item[7]);

            $om->persist($p);

            $this->addReference('project_'.$key, $p);
        }

        $om->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
