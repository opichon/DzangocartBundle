<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;
use Dzangocart\Bundle\DzangocartBundle\Form\Type\SalesFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class SaleController extends AbstractDzangocartController
{
    /**
     * @Route("/", name="dzangocart_sales")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filters = $this->createForm(
            new SalesFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime(),
            )
        );

        return array(
            'filters' => $filters->createView(),
            'config' => $this->getDzangocartConfig(),
        );
    }

    /**
     * @Route("/list", name="dzangocart_sales_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template()
     */
    public function listAction(Request $request)
    {
        $params = array(
            'limit' => $request->query->get('length'),
            'offset' => $request->query->get('start'),
        );

        $params = array_merge(
            $params,
            $this->getFilters($request)
        );

        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getSales($params);

        $data['datetime_format'] = $this->getDzangocartConfig('datetime_format');

        return $data;
    }

    protected function getFilters(Request $request)
    {
        $filters = array();

        $search_values = $request->query->get('filters');

        $search_columns = $this->getSearchColumns();

        foreach ($search_values as $name => $value) {
            if (array_key_exists($name, $search_columns)) {
                $filters[$search_columns[$name]] = $value;

        /*
        foreach ($date_fields = array('date_from', 'date_to') as $field) {
            $value = @$_filters[$field];
            if (!empty($value)) {
                $filters[$field] = $value;
            }
        }
        */

        return $filters;
    }

    protected function getSearchColumns()
    {
        return array(
            'date_from' => 'date_from',
            'date_to' => 'date_to',
            'test' => 'test',
            'order_id' => 'order_id',
            'customer_id' => 'customer',
            'name' => 'name',
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
                $sort[] = $columns[$index];
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
            4 => 'name',
            6 => 'currency',
            7 => 'amount_excl',
            8 => 'tax_amount',
            9 => 'amount_incl',
            11 => 'affiliate',
            12 => 'test',
        );
    }
}
