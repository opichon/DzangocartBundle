<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class CatalogueController
{
    protected $dzangocart;
    protected $form_factory;
    protected $router;

    public function __construct($dzangocart, FormFactory $form_factory, Router $router)
    {
        $this->dzangocart = $dzangocart;
        $this->form_factory = $form_factory;
        $this->router = $router;
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
            $category,
            array(
                'action' => $this->router->generate(
                    'dzangocart_category_update',
                    array('id' => $id)
                ),
                'method' => 'POST'
            )
        );

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Template("DzangocartBundle:Catalogue:show.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $form = $this->form_factory->create(
            new CategoryFormType()
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            // TODO Display flash success message.
        }

        $category = $request->get('category');

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }
}
