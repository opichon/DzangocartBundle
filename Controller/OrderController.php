<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;
use Dzangocart\Bundle\DzangocartBundle\Form\Type\OrdersFilterType;
use Dzangocart\Bundle\DzangocartBundle\Propel\OrderManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use UAM\Bundle\DatatablesBundle\Controller\DatatablesEnabledControllerTrait;

class OrderController extends AbstractDzangocartController
{
    use DatatablesEnabledControllerTrait {
        indexAction as baseIndexAction;
    }

    /**
     * Lists all orders.
     *
     * @Template("DzangocartBundle:Order:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }

    /**
     * @Template("DzangocartBundle:Order:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $manager = $this->getEntityManager();

        $data = $manager->getEntities($request);

        return $data;
    }

    /**
     */
    public function indexOldAction(Request $request)
    {
        $filters = $this->createForm(
            new OrdersFilterType(),
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
     * @Template("DzangocartBundle:Order:list.json.twig")
     */
    public function listOldAction(Request $request)
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

        $data = $this->container->get('dzangocart')
            ->getOrders($params);

        $data['datetime_format'] = $this->getDzangocartConfig('datetime_format');

        return $data;
    }

    /**
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $params = array(
            'id' => $id,
        );

        $order = $this->container->get('dzangocart')
            ->getOrder($params);

        return array(
            'order' => $order,
            'config' => $this->getDzangocartConfig(),
            'data' => print_r($order, true),
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
            'customer_id' => 'customer',
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
            4 => 'currency',
            5 => 'amount_excl',
            6 => 'tax_amount',
            7 => 'amount_incl',
            9 => 'affiliate',
            10 => 'test',
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

    /**
     * @inheritdoc
     */
    protected function getEntityManager()
    {
        return new OrderManager($this->get('dzangocart'));
    }

    protected function getFilter(Request $request)
    {
        if ($filter_type = $this->getEntityManager()->getFilterType($request)) {
            return $this->createForm(
                $filter_type,
                array(
                    'date_from' => date_create(date('Y').'-01-01'),
                    'date_to' => date_create(date('Y').'-12-31'),
                )
            );
        }
    }
}
