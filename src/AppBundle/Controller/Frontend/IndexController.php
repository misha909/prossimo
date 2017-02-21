<?php

namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Frontend\Index Controler
 *
 * @Route("/")
 *
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Template(":Frontend:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return [];
    }
}
