<?php
namespace AppBundle\Model;
use AppBundle\Entity\FillingType;

class FillingTypeCollection
{
    /**
     * @var FillingType[]
     */
    public $filling_types;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param FillingType[]  $fillingTypes
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($fillingTypes = array(), $offset = null, $limit = null)
    {
        $this->filling_types = $fillingTypes;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
