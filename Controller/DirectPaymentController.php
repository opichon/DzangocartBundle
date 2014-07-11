<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\DirectPaymentFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 * @Template
 */
class DirectPaymentController extends Controller
{
    /**
     * @Route("/", name="dzangocart_direct_payments")
     * @Template("DzangocartBundle:DirectPayment:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $form = $this->createForm(
            new DirectPaymentFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            )
        );

        return array(
            'form' => $form->createView(),
            'config' => $dzangocart_config
        );

    }

        /**
     * @Route("/list", name="dzangocart_direct_payments_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template("DzangocartBundle:DirectPayment:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $params = array(
            'limit' => $request->query->get('length'),
            'offset' => $request->query->get('start')
        );

        $params = array_merge(
            $params,
            $this->getFilters($request)
        );

        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getDirectPayments($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        $view = $this->renderView('DzangocartBundle:DirectPayment:index.json.twig', $data);

        return new Response($view, 200, array('Content-Type' => 'application/json'));

    }

    protected function getFilters(Request $request)
    {
        $filters = array();

        $search_values = $request->query->get('filters');

        $search_columns = $this->getSearchColumns();

        foreach ($search_values as $name => $value) {
            if (array_key_exists($name, $search_columns)) {
                $filters[$search_columns[$name]] = $value;
            }
        }

        return $filters;
    }

    protected function getSortOrder(Request $request)
    {
        $sort = array();

        $order = $request->query->get('order');

        $columns = $this->getSortColumns();

        foreach ($order as $setting) {

            $index = $setting['column'];

            if (isset($columns[$index])) {
                $sort[] = $columns[$index] ;
                $sort[] = $setting['dir'];
            }
        }

        return implode(',', $sort);
    }

        protected function getSortColumns()
    {
        return array(
            1 => 'date',
            2 => 'order_id',
            3 => 'amount',
            4 => 'bank',
            5 => 'type',
            6 => 'cheque',
            7 => 'test'
        );
    }

    protected function getSearchColumns()
    {
        return array(
            'date_from' => 'date_from',
            'date_to' => 'date_to',
            'test' => 'test'
        );
    }
}
