<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogueController extends Controller
{
    /**
     * @Route("/", name="catalogue")
     * @Template("DzangocartBundle:Catalogue:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
