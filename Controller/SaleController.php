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
class SaleController extends Controller
{
    /**
     * @Route("/", name="dzangocart_sales")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {
            $data = $this->get('dzangocart')
                ->getSales(array(
                    'limit' => $request->query->get('iDisplayLength'),
                    'offset' => $request->query->get('iDisplayStart')
                ));

            $view = $this->renderView('DzangocartBundle:Sale:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        }
        else {
            $data = $this->get('dzangocart')
            ->getSales();

            return array(
                'data' => print_r($data, true)
            );
        }

    }
}