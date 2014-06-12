<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\SaleFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    /**
     * @Route("/", name="dzangocart_sales")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $form = $this->createForm(
            new SaleFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            ),
            array()
        );

        return array(
            'form' => $form->createView(),
            'config' => $dzangocart_config
        );
    }

    /**
     * @Route("/list", name="dzangocart_sales_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template()
     */
    public function listAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $params = $this->getFilters($request->query);
        $params['sort_by'] = $this->getSortOrder($request->query);

        $data = $this->get('dzangocart')
            ->getSales($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        return $data;
    }

    protected function getFilters(ParameterBag $query)
    {
        $filters = array();

        $filters['search'] = $query->get('search');
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

    protected function getSortOrder(ParameterBag $query)
    {
        $sort_by = array();

        $columns = $this->getSortColumns();

        $n = $query->get('sortingCols');

        for ($i = 0; $i < $n; $i++) {
            $index = $query->get('sortCol_' . $i);

            if (array_key_exists($index, $columns)) {

                $column = $columns[$index];

                if (!is_array($column)) {
                    $column = array($column);
                }

                foreach ($column as $c) {
                    $sort_by[] = $c;
                    $sort_by[] = $query->get('sortDir_' . $i, 'asc');
                }
            }
        }

        if (empty($sort_by)) {
            $sort_by = $this->getDefaultSortOrder();
        }

        return $sort_by;
    }

    protected function getDefaultSortOrder()
    {
        return array('cart.DATE', 'asc');
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'cart.DATE',
            2 => 'item.ORDER_ID',
            3 => array('user_profile.SURNAME', 'user_profile.GIVEN_NAMES'),
            4 => 'item.NAME',
            6 => 'cart.CURRENCY_ID',
            7 => 'item.AMOUNT_EXCL',
            8 => 'item.TAX_AMOUNT',
            9 => 'item.AMOUNT_INCL',
            11 => 'cart.AFFILIATE_ID',
            11 => 'cart.TEST'
        );
    }
}
