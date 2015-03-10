<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\SipsFiltersType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/sips")
 * @Template
 */
class SipsController extends AbstractDzangocartController
{
    /**
     * @Route("/", name="dzangocart_sips")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filters = $this->createForm(
            new SipsFiltersType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            ),
            array()
        );

        return array(
            'filters' => $filters->createView(),
            'config' => $this->getDzangocartConfig()
        );
    }

    /**
     * @Route("/list", name="dzangocart_sips_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template()
     */
    public function listAction(Request $request)
    {
        $params = $this->getFilters($request);
        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getSips($params);

        $data['datetime_format'] = $this->getDzangocartConfig('datetime_format');

        return $data;
    }

    protected function getFilters(Request $request)
    {
        $filters = array();

        $filters['limit'] = min(100, $request->query->get('length', 10));
        $filters['offset'] = max(0, $request->query->get('start', 0));

        $_filters = $request->query->get('filters');

        if ($_filters) {
            foreach ($date_fields = array('date_from', 'date_to') as $field) {
                $value = $_filters[$field];
                if (!empty($value)) {
                    $filters[$field] = $value;
                }
            }
        }

        if (!@$_filters['test']) {
            $filters['merchant_id'] = $this->getDzangocartConfig('sips')['merchant_id'];
        }

        return $filters;
    }

    protected function getSortOrder(Request $request)
    {
        $sort_by = array();

        $columns = $this->getSortColumns();

        $order = $request->query->get('order', array());

        foreach ($order as $setting) {

            $index = $setting['column'];

            if (array_key_exists($index, $columns)) {
                $sort_by[] = $columns[1] ;
                $sort_by[] = $columns['dir'];
            }
        }

        return implode(',', $sort_by);
    }

    protected function getDefaultSortOrder()
    {
        return array('cart.DATE', 'asc');
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'date',
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
