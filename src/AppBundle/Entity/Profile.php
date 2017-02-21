<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="profiles")
 */
class Profile
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->units = new ArrayCollection();
    }

    /**
     * String representation of object
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $unit_type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $system;

    /**
     * @var float
     *
     * @ORM\Column(name="frame_width", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $frame_width;

    /**
     * @var float
     *
     * @ORM\Column(name="mullion_width", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $mullion_width;

    /**
     * @var float
     *
     * @ORM\Column(name="sash_frame_width", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $sash_frame_width;

    /**
     * @var float
     *
     * @ORM\Column(name="sash_frame_overlap", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $sash_frame_overlap;

    /**
     * @var float
     *
     * @ORM\Column(name="sash_mullion_overlap", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $sash_mullion_overlap;

    /**
     * @var string
     *
     * @ORM\Column(name="frame_corners", type="string", length=255, nullable=true)
     */
    protected $frame_corners;

    /**
     * @var string
     *
     * @ORM\Column(name="sash_corners", type="string", length=255, nullable=true)
     */
    protected $sash_corners;

    /**
     * @var float
     *
     * @ORM\Column(name="threshold_width", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $threshold_width;

    /**
     * @var boolean
     *
     * @ORM\Column(name="low_threshold", type="boolean", nullable=true)
     */
    protected $low_threshold;

    /**
     * @var float
     *
     * @ORM\Column(name="frame_u_value", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $frame_u_value;

    /**
     * @var float
     *
     * @ORM\Column(name="spacer_thermal_bridge_value", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $spacer_thermal_bridge_value;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Unit", mappedBy="profile", cascade={"persist", "remove"})
     **/
    protected $units;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $supplier_system;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     * @return Profile
     */
    public function setId($value)
    {
        $this->id = $value;
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
     * @return Profile
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitType()
    {
        return $this->unit_type;
    }

    /**
     * @param string $value
     * @return Profile
     */
    public function setUnitType($value)
    {
        $this->unit_type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param string $value
     * @return Profile
     */
    public function setSystem($value)
    {
        $this->system = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getFrameWidth()
    {
        return $this->frame_width;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setFrameWidth($value)
    {
        $this->frame_width = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getMullionWidth()
    {
        return $this->mullion_width;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setMullionWidth($value)
    {
        $this->mullion_width = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getSashFrameWidth()
    {
        return $this->sash_frame_width;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setSashFrameWidth($value)
    {
        $this->sash_frame_width = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getSashFrameOverlap()
    {
        return $this->sash_frame_overlap;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setSashFrameOverlap($value)
    {
        $this->sash_frame_overlap = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getSashMullionOverlap()
    {
        return $this->sash_mullion_overlap;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setSashMullionOverlap($value)
    {
        $this->sash_mullion_overlap = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrameCorners()
    {
        return $this->frame_corners;
    }

    /**
     * @param string $value
     * @return Profile
     */
    public function setFrameCorners($value)
    {
        $this->frame_corners = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSashCorners()
    {
        return $this->sash_corners;
    }

    /**
     * @param string $value
     * @return Profile
     */
    public function setSashCorners($value)
    {
        $this->sash_corners = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getThresholdWidth()
    {
        return $this->threshold_width;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setThresholdWidth($value)
    {
        $this->threshold_width = $value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLowThreshold()
    {
        return $this->low_threshold;
    }

    /**
     * @param boolean $value
     * @return Profile
     */
    public function setLowThreshold($value)
    {
        $this->low_threshold = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getFrameUValue()
    {
        return $this->frame_u_value;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setFrameUValue($value)
    {
        $this->frame_u_value = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpacerThermalBridgeValue()
    {
        return $this->spacer_thermal_bridge_value;
    }

    /**
     * @param float $value
     * @return Profile
     */
    public function setSpacerThermalBridgeValue($value)
    {
        $this->spacer_thermal_bridge_value = $value;
        return $this;
    }


    /**
     * Add unit
     *
     * @param Unit $value
     * @return Profile
     */
    public function addWindow(Unit $value)
    {
        $value->setProfile($this);
        $this->units[] = $value;
        return $this;
    }

    /**
     * Remove unit
     *
     * @param Unit $value
     */
    public function removeWindow(Unit $value)
    {
        $value->setProfile(null);
        $this->units->removeElement($value);
    }

    /**
     * Set units
     *
     * @param ArrayCollection $value
     * @return array Unit
     */
    public function setWindows(ArrayCollection $value)  {
        foreach($value as $item) {
            $item->setProfile($this);
        }
        $this->units = $value;
        return $this;
    }

    /**
     * Returns units
     *
     * @return array Unit
     */
    public function getWindows() {
        return $this->units;
    }

    /**
     * @return string
     */
    public function getSupplierSystem()
    {
        return $this->supplier_system;
    }

    /**
     * @param string $value
     * @return Profile
     */
    public function setSupplierSystem($value)
    {
        $this->supplier_system = $value;
        return $this;
    }
}
