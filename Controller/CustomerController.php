<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Exception;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CustomersFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    /**
     * @Route("/", name="dzangocart_customers")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filters = $this->createForm(
            new CustomersFilterType(),
            array()
        );

        return array(
            'filters' => $filters->createView()
        );
    }

    /**
     * @Route("/list", name="dzangocart_customers_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template()
     */
    public function listAction(Request $request)
    {
        $params = array(
            'length' => $request->query->get('length'),
            'start' => $request->query->get('start')
        );

        $params = array_merge(
            $params,
            $this->getFilters($request)
        );

        $params['sort_by'] = $this->getSortOrder($request);

        $data = $this->get('dzangocart')
            ->getCustomers($params);

        return $data;
    }

    /**
     * @Route("/{id}", name="dzangocart_customer", requirements={"id": "\d+"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $params = array(
            'id' => $id
        );

        try {
            $customer = $this->get('dzangocart')
                ->getCustomer($params);

        } catch (Exception $ex) {
            throw new Exception(
                $ex->getResponse()->getReasonPhrase(),
                $ex->getCode(),
                $ex->getPrevious()
            );
        }

        return array(
            'customer' => $customer['data']
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
            'customer_id' => 'customer',
            'gender' => 'gender',
            'email' => 'email'
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
                $sort[] = $columns[$index] ;
                $sort[] = $setting['dir'];
            }
        }

        return implode(',', $sort);
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'customer',
            2 => 'gender',
            3 => 'email'
        );
    }
}
