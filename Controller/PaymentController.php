<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\DirectPaymentFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 * @Template
 */
class PaymentController extends Controller
{
    /**
     * @Route("/", name="dzangocart_payments")
     * @Template()
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
     * @Route("/list", name="dzangocart_payments_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template("DzangocartBundle:Payment:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $params = $this->getFilters($request->query, $dzangocart_config);
        $params['sort_by'] = $this->getSortOrder($request->query);

        $data = $this->get('dzangocart')
            ->getPayments($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        return $data;
    }
    
    protected function getFilters(ParameterBag $query, $config)
    {
        $filters = array();

        $filters['length'] = $query->get('length');
        $filters['start'] = $query->get('start');

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
        return array('payment.createdAt', 'asc');
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'payment.orderId',
            2 => 'payment.createdAt'
        );
    }
}
