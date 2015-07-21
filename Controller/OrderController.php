<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

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

    /**
     * @inheritdoc
     */
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
