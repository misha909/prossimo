<?php
namespace AppBundle\Model;

use AppBundle\Entity\AppSetting;
use Doctrine\Common\Collections\ArrayCollection;

class AppSettingsCollection
{
    /**
     * @var AppSetting[]
     */
    public $app_settings;
    /**
     * @var integer
     */
    public $offset;
    /**
     * @var integer
     */
    public $limit;

    /**
     * ctor
     */
    public function __construct($app_setting = array())
    {
        $this->app_settings = new ArrayCollection($app_setting);
        $this->offset = 0;
        $this->limit = 0;
    }

    public function getAppSettings() {
        return $this->app_settings;
    }
}
