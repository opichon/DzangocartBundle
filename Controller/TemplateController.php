<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

trait TemplateController
{
    public function getBaseTemplate()
    {
        return "DzangocartBundle::admin.html.twig";
    }
}
