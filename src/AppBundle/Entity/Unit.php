<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="units")
 */
class Unit
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
    protected $mark;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $width;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $height;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_name", type="string", length=255, nullable=true)
     */
    protected $profile_name;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_image", type="text", nullable=true)
     */
    protected $customer_image;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_color", type="string", length=50, nullable=true)
     */
    protected $internal_color;

    /**
     * @var string
     *
     * @ORM\Column(name="external_color", type="string", length=50, nullable=true)
     */
    protected $external_color;

    /**
     * @var string
     *
     * @ORM\Column(name="interior_handle", type="string", length=255, nullable=true)
     */
    protected $interior_handle;

    /**
     * @var string
     *
     * @ORM\Column(name="exterior_handle", type="string", length=255, nullable=true)
     */
    protected $exterior_handle;

    /**
     * @var string
     *
     * @ORM\Column(name="hardware_type", type="string", length=255, nullable=true)
     */
    protected $hardware_type;

    /**
     * @var string
     *
     * @ORM\Column(name="lock_mechanism", type="string", length=255, nullable=true)
     */
    protected $lock_mechanism;

    /**
     * @var string
     *
     * @ORM\Column(name="glazing_bead", type="string", length=255, nullable=true)
     */
    protected $glazing_bead;

    /**
     * @var string
     *
     * @ORM\Column(name="gasket_color", type="string", length=50, nullable=true)
     */
    protected $gasket_color;

    /**
     * @var string
     *
     * @ORM\Column(name="hinge_style", type="string", length=255, nullable=true)
     */
    protected $hinge_style;

    /**
     * @var string
     *
     * @ORM\Column(name="opening_direction", type="string", length=50, nullable=true)
     */
    protected $opening_direction;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_sill", type="string", length=255, nullable=true)
     */
    protected $internal_sill;

    /**
     * @var string
     *
     * @ORM\Column(name="external_sill", type="string", length=255, nullable=true)
     */
    protected $external_sill;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $glazing;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $uw;

    /**
     * @var float
     *
     * @ORM\Column(name="original_cost", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $original_cost;

    /**
     * @var string
     *
     * @ORM\Column(name="original_currency", type="string", length=100, nullable=true)
     */
    protected $original_currency;

    /**
     * @var float
     *
     * @ORM\Column(name="conversion_rate", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $conversion_rate;

    /**
     * @var float
     *
     * @ORM\Column(name="price_markup", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $price_markup;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=7, scale=4, nullable=true)
     */
    protected $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="root_section", type="text", length=8192, nullable=true)
     */
    protected $root_section;

    /**
     * @var float
     *
     * @ORM\Column(name="glazing_bar_width", type="decimal", precision=19, scale=4, nullable=true)
     */
    protected $glazing_bar_width;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="units")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Profile", inversedBy="units")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     * @return Unit
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setMark($value)
    {
        $this->mark = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setWidth($value)
    {
        $this->width = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setHeight($value)
    {
        $this->height = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $value
     * @return Unit
     */
    public function setQuantity($value)
    {
        $this->quantity = $value;
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
     * @return Unit
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setNotes($value)
    {
        $this->notes = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileName()
    {
        return $this->profile_name;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setProfileName($value)
    {
        $this->profile_name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerImage()
    {
        return $this->customer_image;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setCustomerImage($value)
    {
        $this->customer_image = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalColor()
    {
        return $this->internal_color;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setInternalColor($value)
    {
        $this->internal_color = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalColor()
    {
        return $this->external_color;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setExternalColor($value)
    {
        $this->external_color = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getInteriorHandle()
    {
        return $this->interior_handle;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setInteriorHandle($value)
    {
        $this->interior_handle = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getExteriorHandle()
    {
        return $this->exterior_handle;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setExteriorHandle($value)
    {
        $this->exterior_handle = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getHardwareType()
    {
        return $this->hardware_type;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setHardwareType($value)
    {
        $this->hardware_type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getLockMechanism()
    {
        return $this->lock_mechanism;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setLockMechanism($value)
    {
        $this->lock_mechanism = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGlazingBead()
    {
        return $this->glazing_bead;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setGlazingBead($value)
    {
        $this->glazing_bead = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGasketColor()
    {
        return $this->gasket_color;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setGasketColor($value)
    {
        $this->gasket_color = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getHingeStyle()
    {
        return $this->hinge_style;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setHingeStyle($value)
    {
        $this->hinge_style = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpeningDirection()
    {
        return $this->opening_direction;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setOpeningDirection($value)
    {
        $this->opening_direction = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalSill()
    {
        return $this->internal_sill;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setInternalSill($value)
    {
        $this->internal_sill = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalSill()
    {
        return $this->external_sill;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setExternalSill($value)
    {
        $this->external_sill = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGlazing()
    {
        return $this->glazing;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setGlazing($value)
    {
        $this->glazing = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getUw()
    {
        return $this->uw;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setUw($value)
    {
        $this->uw = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getOriginalCost()
    {
        return $this->original_cost;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setOriginalCost($value)
    {
        $this->original_cost = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalCurrency()
    {
        return $this->original_currency;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setOriginalCurrency($value)
    {
        $this->original_currency = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getConversionRate()
    {
        return $this->conversion_rate;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setConversionRate($value)
    {
        $this->conversion_rate = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceMarkup()
    {
        return $this->price_markup;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setPriceMarkup($value)
    {
        $this->price_markup = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setDiscount($value)
    {
        $this->discount = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootSection()
    {
        return $this->root_section;
    }

    /**
     * @param string $value
     * @return Unit
     */
    public function setRootSection($value)
    {
        $this->root_section = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getGlazingBarWidth()
    {
        return $this->glazing_bar_width;
    }

    /**
     * @param float $value
     * @return Unit
     */
    public function setGlazingBarWidth($value)
    {
        $this->glazing_bar_width = $value;
        return $this;
    }

    /**
     * Set project
     *
     * @param Project $value
     * @return Unit
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
     * Set profile
     *
     * @param Profile $value
     * @return Unit
     */
    public function setProfile(Profile $value = null)
    {
        $this->profile = $value;
        return $this;
    }

    /**
     * Get profile
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
