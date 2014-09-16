<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\OrdersFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class OrderController
{
    protected $dzangocart;
    protected $dzangocart_config;
    protected $form_factory;

    public function __construct($dzangocart, $dzangocart_config, FormFactory $form_factory)
    {
        $this->dzangocart = $dzangocart;
        $this->dzangocart_config = $dzangocart_config;
        $this->form_factory = $form_factory;
    }

    /**
     * @Template("DzangocartBundle:Order:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $filters = $this->form_factory->create(
            new OrdersFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            )
        );

        return array(
            'filters' => $filters->createView(),
            'config' => $this->dzangocart_config
        );
    }

    /**
     * @Template("DzangocartBundle:Order:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $params = array(
            'limit' => $request->query->get('length'),
            'offset' => $request->query->get('start')
        );

        $params = array_merge(
            $params,
            $this->getFilters($request)
        );

        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->dzangocart
            ->getOrders($params);

        $data['datetime_format'] = $this->dzangocart_config['datetime_format'];

        return $data;
    }

    /**
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $params = array(
            'id' => $id
        );

        $order = $this->dzangocart
            ->getOrder($params);

        return array(
            'order' => $order,
            'config' => $this->dzangocart_config,
            'data' => print_r($order, true)
        );
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

    protected function getSearchColumns()
    {
        return array(
            'date_from' => 'date_from',
            'date_to' => 'date_to',
            'test' => 'test',
            'order_id' => 'order_id',
            'customer_id' => 'customer'
        );
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
            3 => 'customer',
            4 => 'currency',
            5 => 'amount_excl',
            6 => 'tax_amount',
            7 => 'amount_incl',
            9 => 'affiliate',
            10 => 'test'
        );
    }

//    /**
//     * @Template("DzangocartBundle:Order:search.json.twig")
//     */
//    public function searchAction(Request $request, $search = '%QUERY')
//    {
//        $params = array(
//            'search' => $search
//        );
//
//        $customers = $this->dzangocart
//            ->getCustomer($params);
//
//        return array(
//            'customers' => $customers
//        );
//    }
}
