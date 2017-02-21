<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/dashboard")
 *
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     * @Method("GET")
     * @Template("Admin/Dashboard/index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }
}
