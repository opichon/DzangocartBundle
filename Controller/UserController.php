<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
     */
    public function purchasesAction(Request $request)
    {
        $customer = $this->getUser()->getId();

        $params = array(
            'limit' => $request->query->get('length'),
            'offset' => $request->query->get('start'),
            'customer' => $customer
        );

        $purchases = $this->get('dzangocart')
            ->getSales($params);

        return array(
            'purchases' => $purchases['data']
        );
    }

    /**
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $params = array(
            'id' => $id
        );

        $order = $this->get('dzangocart')
            ->getOrder($params);

        return array(
            'order' => $order,
            'config' => $dzangocart_config,
            'data' => print_r($order, true)
        );
    }
}
