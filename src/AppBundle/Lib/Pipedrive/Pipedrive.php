<?php 
namespace AppBundle\Lib\Pipedrive;

//require_once 'Library/Curl.php';
//require_once 'Library/Activities.php';
//require_once 'Library/DealFields.php';
//require_once 'Library/Deals.php';
//require_once 'Library/Files.php';
//require_once 'Library/Notes.php';
//require_once 'Library/Organizations.php';
//require_once 'Library/Persons.php';
//require_once 'Library/Products.php';

/**
 * Pipedrive API wrapper
 */

class Pipedrive
{
    /**
     * API Key
     * @var string
     */
    protected $apiKey;
    /**
     * Protocol (default 'https')
     * @var string
     */
    protected $protocol;
    /**
     * Host Url (default 'api.pipedrive,com')
     * @var string
     */
    protected $host;
    /**
     * API version (default 'v1')
     * @var string
     */
    protected $version;
    /**
     * Hold the Curl Object
     * @var \AppBundle\Lib\Pipedrive\Library\Curl Curl Object
     */
    protected $curl;
    /**
     * Placeholder attritube for the pipedrive persons class
     * @var \AppBundle\Lib\Pipedrive\Library\Persons Persons Object
     */
    protected $persons;
    /**
     * Placeholder attritube for the pipedrive deals class
     * @var \AppBundle\Lib\Pipedrive\Library\Deals Deals Object
     */
    protected $deals;
    /**
     * Placeholder attritube for the pipedrive files class
     * @var \AppBundle\Lib\Pipedrive\Library\Files Files Object
     */
    protected $files;
    /**
     * Placeholder attritube for the pipedrive activities class
     * @var \AppBundle\Lib\Pipedrive\Library\Activities Activities Object
     */
    protected $activities;
    /**
     * Placeholder attritube for the pipedrive notes class
     * @var \AppBundle\Lib\Pipedrive\Library\Notes Notes Object
     */
    protected $notes;
    /**
     * Placeholder attritube for the pipedrive dealFields class
     * @var \AppBundle\Lib\Pipedrive\Library\DealFields DealFields Object
     */
    protected $dealFields;
    /**
     * Placeholder attritube for the pipedrive organizations class
     * @var \AppBundle\Lib\Pipedrive\Library\Organizations Object
     */
    protected $organizations;
    /**
     * Placeholder attritube for the pipedrive products class
     * @var \AppBundle\Lib\Pipedrive\Library\Products Object
     */
    protected $products;

    /**
     * Set up API url and load library classes
     *
     * @param string $apiKey   API key
     * @param string $protocol protocol (default: https)
     * @param string $host     host url (default: api.pipedrive.com)
     * @param string $version  version  (default: v1)
     */
    public function __construct($apiKey = '', $protocol = 'https', $host = 'api.pipedrive.com', $version = 'v1')
    {
        //set var apiKey is essiantial!!
        $this->apiKey   = $apiKey;
        $this->protocol = $protocol;
        $this->host     = $host;
        $this->version  = $version;

        //make API url
        $url = $protocol . '://' . $host . '/' . $version;

        //add curl library and pass the API Url & key to the object
        $this->curl = new Library\Curl($url, $apiKey);

        //add pipedrive classes to the assoicated property
        $this->persons       = new Library\Persons($this);
        $this->deals         = new Library\Deals($this);
        $this->files         = new Library\Files($this);
        $this->activities    = new Library\Activities($this);
        $this->notes         = new Library\Notes($this);
        $this->dealFields    = new Library\DealFields($this);
        $this->organizations = new Library\Organizations($this);
        $this->products      = new Library\Products($this);
    }

    /**
     * Returns the Pipedrive cURL Session
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Curl
     */
    public function curl()
    {
        return $this->curl;
    }

    /**
     * Returns the Pipedrive Persons Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Persons
     */
    public function persons()
    {
        return $this->persons;
    }

    /**
     * Returns the Pipedrive Deals Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Deals
     */
    public function deals()
    {
        return $this->deals;
    }

    /**
     * Returns the Pipedrive Files Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Files
     */
    public function files()
    {
        return $this->files;
    }
    
    /**
     * Returns the Pipedrive Activities Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Activities
     */
    public function activities()
    {
        return $this->activities;
    }

    /**
     * Returns the Pipedrive Notes Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Notes
     */
    public function notes()
    {
        return $this->notes;
    }

    /**
     * Returns the Pipedrive DealFields Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\DealFields
     */
    public function dealFields()
    {
        return $this->dealFields;
    }

    /**
     * Returns the Pipedrive Organizations Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Organizations Object
     */
    public function organizations()
    {
        return $this->organizations;
    }

    /**
     * Returns the Pipedrive Products Object
     *
     * @return \AppBundle\Lib\Pipedrive\Library\Products Object
     */
    public function products()
    {
        return $this->products;
    }
}