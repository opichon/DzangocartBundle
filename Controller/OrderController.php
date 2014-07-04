<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\OrderFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
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
        $form = $this->form_factory->create(
            new OrderFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            )
        );

        return array(
            'form' => $form->createView(),
            'config' => $this->dzangocart_config
        );
    }

    /**
     * @Template("DzangocartBundle:Order:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $params = $this->getFilters($request->query);
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

    protected function getFilters(ParameterBag $query)
    {
        $filters = array();

        $filters['limit'] = $query->get('length');
        $filters['offset'] = $query->get('start');

        $_filters = $query->get('filters');

        foreach ($date_fields = array('date_from', 'date_to') as $field) {
            $value = $_filters[$field];

            if (!empty($value)) {
                $filters[$field] = $value;
            }
        }

        $filters['test'] = @$_filters['test'] ? true : false;

        if (array_key_exists('customer', $_filters)) {
            $filters['customer'] = $_filters['customer'];
        }

        return $filters;
    }

    protected function getSortOrder(Request $request)
    {
        $sort_by = array();

        $order = $request->query->get('order');

        $columns = $this->getSortColumns();
        $count = 0;
        foreach ($order as $setting) {

            $index = $setting['column'];

            if (!array_key_exists($index, $columns)) {
                $sort_by[] = $columns[1] ;
                $sort_by[] = 'asc';

                return implode(',', $sort_by);
            }

            $sort_by[] = $columns[$index] ;
            $sort_by[] = $setting['dir'];
            $count++;
        }

        return implode(',', $sort_by);

    }

    protected function getSortColumns()
    {
        return array(
            1 => 'Cart.date',
            2 => 'Cart.id',
            3 => array('user_profile.surname', 'user_profile.given_names'),
            4 => 'Cart.currency_id',
            5 => 'Cart.amount_excl',
            6 => 'Cart.tax_amount',
            7 => 'Cart.amount_incl',
            9 => 'Cart.affiliate_id',
            10 => 'Cart.test'
        );
    }
}
