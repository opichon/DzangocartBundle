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
	 * @Route("/", name="orders")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$orders = $this->get('dzangocart')->getOrders();

		return array(
			'orders' => $orders
		);
	}
}