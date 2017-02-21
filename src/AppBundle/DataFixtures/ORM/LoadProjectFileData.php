<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\File;

class LoadProjectFileData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        //$name, $type, $url, $project
        $data = [
            ['developer-specs-REV1_2_Public.pdf', 'pdf', '/test/pdf/developer-specs-REV1_2_Public.pdf','project_0'],
            ['helloworld.pdf', 'pdf', '/test/pdf/helloworld.pdf','project_0'],
        ];

        foreach ($data as $key => $item) {
            $e = new File();
            $e->setName($item[0]);
            $e->setType($item[1]);
            $e->setUrl($item[2]);
            $e->setProject($this->getReference($item[3]));

            $om->persist($e);

            $this->addReference('project_file_'.$key, $e);
        }

        $om->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}
