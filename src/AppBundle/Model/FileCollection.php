<?php
namespace AppBundle\Model;

use AppBundle\Entity\File;

class FileCollection
{
    /**
     * @var File[]
     */
    public $files;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;
    /**
     * @param file[]  $files
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($files = array(), $offset = null, $limit = null)
    {
        $this->files = $files;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
