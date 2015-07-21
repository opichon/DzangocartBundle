<?php

namespace Dzangocart\Bundle\DzangocartBundle\Propel;

use Dzangocart\Bundle\DzangocartBundle\Filter\Type\PaymentFilterType;
use Dzangocart\Client\DzangocartClient;
use Symfony\Component\HttpFoundation\Request;
use UAM\Bundle\DatatablesBundle\Propel\AbstractEntityManager;

class PaymentManager extends AbstractEntityManager
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
            ->getPayments($params);
    }

    /**
     * @inheritdoc
     */
    public function getFilterType(Request $request)
    {
        return new PaymentFilterType();
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
            'gateway_id' => 'gateway_id',
            'order_id' => 'order_id',
            'status' => 'status',
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
            3 => 'gateway',
            4 => 'type',
            6 => 'status',
            7 => 'test',
        );
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultSortOrder(Request $request)
    {
        return array(
            'date',
            'desc',
        );
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
