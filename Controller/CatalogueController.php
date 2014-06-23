<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogueController extends Controller
{   
    /**
     * @Route("/", name="dzangocart_catalogue")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $catalogue = $this->get('dzangocart')
            ->getCatalogue();

        return array(
            'catalogue' => $catalogue
        );
    }

    /**
     * @Route("/category/{id}", name="dzangocart_category", requirements={"id": "\d+"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $form = $this->createForm(
                new CategoryFormType()
        );
        return array(
            "form" => $form->createView()
        );
    }
}
