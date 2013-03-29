<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Dzangocart\Client\DzangocartClient;

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

        $client = $this->get('dzangocart');
        $command = $client
            ->getCommand('getOrders');

        $data = $this->get('dzangocart')
            ->getOrders();

        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {
            $data = $this->get('dzangocart')
                ->getOrders();

            $view = $this->renderView('DzangocartBundle:Order:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        }
        else {
            return array(
                'config' => $this->container->getParameter('dzangocart.config'),
                'class' => get_class($command),
                'config2' => $client->getConfig()->toArray(),
                'token' => $command->getClient()->getConfig('token'),
                'baseUrl' => $client->getBaseUrl(),
                'data' => print_r($data, true)
            );


        }

    }
}