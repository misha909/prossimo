<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Accessory;

class LoadProjectAccessoriesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $om)
    {
        //$description, $quantity, $original_cost, $original_currency, $conversion_rate, $price_markup, $discount, $extras_type, $project
        $data = [
            ['Grey restrictor cable w/key - 4.25" length', 90, 10, 'EUR', 0.91261693, 1.5, 0, null, 'project_0'],
            ['Piece of junk', 5, 15, 'USD', 1, 2, 0, null, 'project_0'],
            ['Optional thingy', 1, 450, 'USD', 1, 2, 0, 'Optional', 'project_0'],
            ['Hidden costs for dealing with annoying client', 1, 2000, 'USD', 1, 1, 0, 'Hidden', 'project_0'],
            ['Shipping to site', 1, 1500, 'USD', 1, 1, 0, 'Shipping', 'project_0'],
            ['VAT', 1, null, null, null, 1.3, null, 'Tax', 'project_0'],
        ];

        foreach ($data as $key => $item) {
            $e = new Accessory();
            $e->setDescription($item[0]);
            $e->setQuantity($item[1]);
            $e->setOriginalCost($item[2]);
            $e->setOriginalCurrency($item[3]);
            $e->setConversionRate($item[4]);
            $e->setPriceMarkup($item[5]);
            $e->setDiscount($item[6]);
            $e->setExtrasType($item[7]);
            $e->setProject($this->getReference($item[8]));

            $om->persist($e);

            $this->addReference('project_accessory_'.$key, $e);
        }

        $om->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
