<?php
namespace AppBundle\Model;
use AppBundle\Entity\Project;

class ProjectCollection
{
    /**
     * @var Project[]
     */
    public $projects;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param Project[]  $projects
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($projects = array(), $offset = null, $limit = null)
    {
        $this->projects = $projects;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
