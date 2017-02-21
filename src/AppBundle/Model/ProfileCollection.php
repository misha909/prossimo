<?php
namespace AppBundle\Model;
use AppBundle\Entity\Profile;

class ProfileCollection
{
    /**
     * @var Profile[]
     */
    public $profiles;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param Profile[]  $profiles
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($profiles = array(), $offset = null, $limit = null)
    {
        $this->profiles = $profiles;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
