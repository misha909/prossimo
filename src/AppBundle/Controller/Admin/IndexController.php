<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 *
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     * @Method("GET")
     * @Template(":Admin:index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }
}
