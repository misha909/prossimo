<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $om)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@prossimo.us');
        $userAdmin->setUsernameCanonical('admin');
        $userAdmin->setPlainPassword('12345678');
        $userAdmin->setEnabled(true);
        $userAdmin->setSuperAdmin(true);

        $om->persist($userAdmin);
        $om->flush();

        $this->addReference('user-admin', $userAdmin);
    }

    public function getOrder()
    {
        return 10;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}