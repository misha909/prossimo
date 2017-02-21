<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files        = new ArrayCollection();
        $this->units        = new ArrayCollection();
        $this->accessories  = new ArrayCollection();
    }

    /**
     * String representation of object
     * @return string
     */
    public function __toString()
    {
        return $this->getProjectName();
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $client_name;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $client_company_name;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $client_phone;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $client_email;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $client_address;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $project_name;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $project_address;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\File", mappedBy="project", cascade={"persist", "remove"})
     **/
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Unit", mappedBy="project", cascade={"persist", "remove"})
     **/
    protected $units;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Accessory", mappedBy="project", cascade={"persist", "remove"})
     **/
    protected $accessories;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $sync_datetime;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return Project
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getClientName()
    {
        return $this->client_name;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setClientName($value)
    {
        $this->client_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientCompanyName()
    {
        return $this->client_company_name;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setClientCompanyName($value)
    {
        $this->client_company_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientPhone()
    {
        return $this->client_phone;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setClientPhone($value)
    {
        $this->client_phone = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientEmail()
    {
        return $this->client_email;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setClientEmail($value)
    {
        $this->client_email = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientAddress()
    {
        return $this->client_address;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setClientAddress($value)
    {
        $this->client_address = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setProjectName($value)
    {
        $this->project_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectAddress()
    {
        return $this->project_address;
    }

    /**
     * @param string $value
     * @return Project
     */
    public function setProjectAddress($value)
    {
        $this->project_address = $value;
        return $this;
    }

    /**
     * Add file
     *
     * @param File $value
     * @return Project
     */
    public function addFile(File $value)
    {
        $value->setProject($this);
        $this->files[] = $value;
        return $this;
    }

    /**
     * Remove file
     *
     * @param File $value
     */
    public function removeFile(File $value)
    {
        $value->setProject(null);
        $this->files->removeElement($value);
    }

    /**
     * Set files
     *
     * @param ArrayCollection $value
     * @return array ProjectFile
     */
    public function setFiles(ArrayCollection $value)  {
        foreach($value as $item) {
            $item->setProject($this);
        }

        $this->files = $value;
        return $this;
    }

    /**
     * Returns files
     *
     * @return array ProjectFile
     */
    public function getFiles() {
        return $this->files;
    }


    /**
     * Add unit
     *
     * @param Unit $value
     * @return Project
     */
    public function addUnit(Unit $value)
    {
        $value->setProject($this);
        $this->units[] = $value;
        return $this;
    }

    /**
     * Remove unit
     *
     * @param Unit $value
     */
    public function removeUnit(Unit $value)
    {
        $value->setProject(null);
        $this->units->removeElement($value);
    }

    /**
     * Set units
     *
     * @param ArrayCollection $value
     * @return array Unit
     */
    public function setUnits(ArrayCollection $value)  {
        foreach($value as $item) {
            $item->setProject($this);
        }
        $this->units = $value;
        return $this;
    }

    /**
     * Returns units
     *
     * @return array Unit
     */
    public function getUnits() {
        return $this->units;
    }


    /**
     * Add accessory
     *
     * @param Accessory $value
     * @return Project
     */
    public function addAccessory(Accessory $value)
    {
        $value->setProject($this);
        $this->accessories[] = $value;
        return $this;
    }

    /**
     * Remove accessory
     *
     * @param Accessory $value
     */
    public function removeAccessory(Accessory $value)
    {
        $value->setProject(null);
        $this->accessories->removeElement($value);
    }

    /**
     * Set accessories
     *
     * @param ArrayCollection $value
     * @return array Accessory
     */
    public function setAccessories(ArrayCollection $value)  {
        foreach($value as $item) {
            $item->setProject($this);
        }
        $this->accessories = $value;
        return $this;
    }

    /**
     * Returns accessories
     *
     * @return array Accessory
     */
    public function getAccessories() {
        return $this->accessories;
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
