<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\SaleFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $params = $this->getFilters($request);

        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getSales($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        return $data;
    }

    protected function getFilters(Request $request)
    {
        $filters = array();

        $filters['limit'] = $request->query->get('length');
        $filters['offset'] = $request->query->get('start');

        $_filters = $request->query->get('filters');

        $fields = array(
            'affiliate_id',
            'category',
            'code',
            'code_generic',
            'customer',
            'date_from',
            'date_to',
            'list_by',
            'name'
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

        $filters['include_subcategories'] = @$_filters['include_subcategories'] ? true : false;

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
