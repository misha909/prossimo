<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    CONST ROLE_ADMIN = 'ROLE_ADMIN';
    CONST ROLE_MANAGER = 'ROLE_MANAGER';
    CONST ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->username = 'username'; //workaround to avoid "username is empty" constraint yelling
    }
}

