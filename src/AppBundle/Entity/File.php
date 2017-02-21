<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="project_files")
 */
class File
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * String representation of object
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $pipedrive_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, unique=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1024, unique=false)
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="files")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $sync_datetime;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     * @return File
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPipedriveId()
    {
        return $this->pipedrive_id;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setPipedriveId($value = null)
    {
        $this->pipedrive_id = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return File
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $value
     * @return File
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $value
     * @return File
     */
    public function setUrl($value)
    {
        $this->url = $value;
        return $this;
    }

    /**
     * Set project
     *
     * @param Project $value
     * @return File
     */
    public function setProject(Project $value = null)
    {
        $this->project = $value;
        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return \Datetime
     */
    public function getSyncDatetime()
    {
        return $this->sync_datetime;
    }

    /**
     * @param \Datetime $value
     * @return Project
     */
    public function setSyncDatetime($value = null)
    {
        $this->sync_datetime = $value;
        return $this;
    }
}
