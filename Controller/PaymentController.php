<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;
use Dzangocart\Bundle\DzangocartBundle\Propel\PaymentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use UAM\Bundle\DatatablesBundle\Controller\DatatablesEnabledControllerTrait;

/**
 * @Template
 */
class PaymentController extends AbstractDzangocartController
{
    use DatatablesEnabledControllerTrait {
        indexAction as baseIndexAction;
    }

    /**
     * Lists all payments.
     *
     * @Template("DzangocartBundle:Payment:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }

    /**
     * @Template("DzangocartBundle:Payment:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $manager = $this->getEntityManager();

        $data = $manager->getEntities($request);

        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function getEntityManager()
    {
        return new PaymentManager($this->get('dzangocart'));
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
