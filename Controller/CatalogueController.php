<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class CatalogueController
{
    protected $dzangocart;
    protected $form_factory;

    public function __construct($dzangocart, FormFactory $form_factory)
    {
        $this->dzangocart = $dzangocart;
        $this->form_factory = $form_factory;
    }

    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $catalogue = $this->dzangocart
            ->getCatalogue();

        return array(
            'catalogue' => $catalogue
        );
    }

    /**
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $params = array(
            'id' => $id
        );

        $category = $this->dzangocart
            ->getCategory($params);

        $form = $this->form_factory->create(
            new CategoryFormType(),
            $category
        );

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Template
     */
    public function updateAction(Request $request, $id)
    {
        return array();
    }
}
