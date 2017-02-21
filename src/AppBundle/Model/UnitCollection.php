<?php
namespace AppBundle\Model;
use AppBundle\Entity\Unit;

class UnitCollection
{
    /**
     * @var Unit[]
     */
    public $units;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param Unit[]  $units
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($units = array(), $offset = null, $limit = null)
    {
        $this->units = $units;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
