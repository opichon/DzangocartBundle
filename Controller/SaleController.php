<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\SaleFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

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
        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getSales($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        return $data;
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
            2 => 'item.order_id',
            3 => array('user_profile.surname', 'user_profile.given_names'),
            4 => 'item.name',
            6 => 'Cart.currency_id',
            7 => 'item.amount_excl',
            8 => 'item.tax_amount',
            9 => 'item.amount_incl',
            11 => 'Cart.affiliate_id',
            11 => 'Cart.test'
        );
    }
}
