<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Porot\Bundle\UserBundle\Model\UserQuery;

/**
 * @Route("/")
 * @Template
 */
class OrderController extends Controller
{
	public function indexAction(Request $request)
	{
		$orders = $this->get('dzangocart')->getORders();

		return array(
			'orders' => $orders
		);
	}
}