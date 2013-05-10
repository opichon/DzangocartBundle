<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 * @Template
 */
class PurchaseController extends Controller
{
    /**
     * @Route("/", name="dzangocart_purchases")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {
            $data = $this->get('dzangocart')
                ->getOrders();

            $view = $this->renderView('DzangocartBundle:Purchase:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        }
        else {
            return array();
        }

    }
}