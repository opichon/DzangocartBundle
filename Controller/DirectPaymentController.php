<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\DirectPaymentFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
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
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {

            $params = $this->getFilters($request->query, $dzangocart_config);
            $params['sort_by'] = $this->getSortOrder($request->query);

            $data = $this->get('dzangocart')
                ->getDirectPayments($params);

            $data['datetime_format'] = $dzangocart_config['datetime_format'];

            $view = $this->renderView('DzangocartBundle:DirectPayment:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        } else {
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
    }

    protected function getFilters(ParameterBag $query, $config)
    {
        $filters = array();

        $filters['limit'] = $query->get('length');
        $filters['offset'] = $query->get('start');

        $_filters = $query->get('filters');

        if ($_filters) {
            foreach ($date_fields = array('date_from', 'date_to') as $field) {
                $value = $_filters[$field];
                if (!empty($value)) {
                    $filters[$field] = $value;
                }
            }
        }

        $filters['test'] = @$_filters['test'] ? true : false;

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
