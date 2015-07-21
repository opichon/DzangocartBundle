<?php

namespace Dzangocart\Bundle\DzangocartBundle\Propel;

use ModelCriteria;
use Dzangocart\Bundle\DzangocartBundle\Filter\Type\OrderFilterType;
use Dzangocart\Client\DzangocartClient;
use Symfony\Component\HttpFoundation\Request;
use UAM\Bundle\DatatablesBundle\Propel\AbstractEntityManager;

class OrderManager extends AbstractEntityManager
{
    protected $client;

    public function __construct(DzangocartClient $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function getEntities(Request $request)
    {
        $params = array(
            'limit' => $this->getLimit($request),
            'offset' => $this->getOffset($request),
        );

        $params = array_merge(
            $params,
            $this->getFilters($request)
        );

        $params['sort_by'] = implode(
            ',',
            $this->getSortOrder($request)
        );

        return $this->getClient()
            ->getOrders($params);
    }

    /**
     * @inheritdoc
     */
    public function getFilterType(Request $request)
    {
        return new OrderFilterType();
    }

    /**
     * @inheritdoc
     */
    protected function getQuery(Request $request)
    {
    }

    /**
     * @inheritdoc
     */
    protected function getSearchColumns(Request $request)
    {
        return array(
            'date_from' => 'date_from',
            'date_to' => 'date_to',
            'test' => 'test',
            'order_id' => 'order_id',
            'customer_id' => 'customer',
        );
    }

    /**
     * @inheritdoc
     */
    protected function getSortColumns(Request $request)
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

    /**
     * @inheritdoc
     */
    protected function getDefaultSortOrder(Request $request)
    {
        return array(
            'date',
            'desc'
        );
    }

    /**
     * @inheridoc
     */
    protected function search(ModelCriteria $query, Request $request)
    {
        $query = parent::search($query, $request);

        $filters = $this->getFilters($request);

        if ($filters['seminar_manager']) {
            $query->isManager();
        }

        return $query;
    }

    protected function getSortOrder(Request $request)
    {
        $sort = array();

        $order = $request->query->get('order', array());

        $columns = $this->getSortColumns($request);

        foreach ($order as $setting) {
            $index = $setting['column'];

            if (array_key_exists($index, $columns)) {
                $column = $columns[$index];

                if (!is_array($column)) {
                    $column = array($column);
                }

                foreach ($column as $c) {
                    $sort[] = $c;
                    $sort[] = $setting['dir'];
                }
            }
        }

        // Default sort order
        if (empty($sort)) {
            $sort = $this->getDefaultSortOrder($request);
        }

        return $sort;
    }

    protected function getClient()
    {
        return $this->client;
    }
}
