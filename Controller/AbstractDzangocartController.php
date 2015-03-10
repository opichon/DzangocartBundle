<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractDzangocartController extends Controller
{
    protected function getDzangocartClient()
    {
        return $this->container->get('dzangocart');
    }

    protected function getDzangocartConfig($key = null)
    {
        $config = $this->container->getParameter('dzangocart.config');

        return $key
            ? $config[$key]
            : $config;
    }
}