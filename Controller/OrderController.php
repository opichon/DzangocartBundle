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
        $params = $this->getFilters($request);
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

        $filters['limit'] = $request->query->get('length');
        $filters['offset'] = $request->query->get('start');

        $_filters = $request->query->get('filters');

        $fields = array(
            'affiliate',
            'category',
            'code',
            'code_generic',
            'customer',
            'date_from',
            'date_to',
            'list_by',
            'name',
            'search'
        );

        foreach ($fields as $field) {
            if (array_key_exists($field, $_filters)) {
                $value = $_filters[$field];

                if (!empty($value)) {
                    $filters[$field] = $value;
                }
            }
        }

        $filters['test'] = @$_filters['test'] ? true : false;

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
            3 => 'customer',
            4 => 'currency',
            5 => 'amount_excl',
            6 => 'tax_amount',
            7 => 'amount_incl',
            9 => 'affiliate',
            10 => 'test'
        );
    }
}
