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
class OrderController extends Controller
{
    /**
     * @Route("/", name="dzangocart_orders")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {
            $data = $this->get('dzangocart')
                ->getOrders(array(
                    'limit' => $request->query->get('iDisplayLength'),
                    'offset' => $request->query->get('iDisplayStart')
                ));

            $view = $this->renderView('DzangocartBundle:Order:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        } else {
            $data = $this->get('dzangocart')
                ->getOrders();

            return array(
                'data' => print_r($data, true)
            );

        }

    }
}