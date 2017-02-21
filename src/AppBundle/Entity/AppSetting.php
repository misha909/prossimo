<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AppSettingRepository")
 */
class AppSetting
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
        return $this->getDisplayName();
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $system_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $display_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $group_name;


    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value;

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
    public function getSystemName()
    {
        return $this->system_name;
    }

    /**
     * @param string $value
     * @return AppSetting
     */
    public function setSystemName($value)
    {
        $this->system_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * @param string $value
     * @return AppSetting
     */
    public function setDisplayName($value)
    {
        $this->display_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @param string $value
     * @return AppSetting
     */
    public function setGroupName($value)
    {
        $this->group_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AppSetting
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
