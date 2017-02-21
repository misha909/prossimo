<?php
namespace AppBundle\Model;
use AppBundle\Entity\Accessory;

class AccessoryCollection
{
    /**
     * @var Accessory[]
     */
    public $windows;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param Accessory[]  $accessories
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($accessories = array(), $offset = null, $limit = null)
    {
        $this->accessories = $accessories;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
