<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
     */
    public function purchasesAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $params = array(
            'limit' => $request->query->get('length'),
            'offset' => $request->query->get('start')
        );

        $data = $this->get('dzangocart')
            ->getSales($params);

        $data['datetime_format'] = $dzangocart_config['datetime_format'];

        return array(
            'data' => $data['data']
        );
    }
}
