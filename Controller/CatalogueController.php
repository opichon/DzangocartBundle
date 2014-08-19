<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Exception;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class CatalogueController
{
    protected $dzangocart;
    protected $form_factory;
    protected $router;
    protected $session;
    protected $translator;

    public function __construct($dzangocart, FormFactory $form_factory, Router $router, Session $session, TranslatorInterface $translator)
    {
        $this->dzangocart = $dzangocart;
        $this->form_factory = $form_factory;
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
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

        try {
            $category = $this->dzangocart
                ->getCategory($params);
        } catch (Exception $e){
            $this->session->getFlashBag()->add(
                'Category.error',
                $this->translator->trans('category.error.not_found', array(), 'dzangocart', $request->getLocale())
            );

            return new RedirectResponse($this->router->generate('dzangocart_catalogue'));
        }

        $form = $this->form_factory->create(
            new CategoryFormType(),
            $category,
            array_merge(
                array(
                    'action' => $this->router->generate(
                        'dzangocart_category_update',
                        array(
                            'id' => $id
                        )
                    )
                ), $this->getDefaultOptions()
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
        // TODO merge this with the showAction?
        $params = array(
            'id' => $id
        );

        $category = $this->dzangocart
            ->getCategory($params);

        $form = $this->form_factory->create(
            new CategoryFormType(),
            $category,
            $this->getDefaultOptions()
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $category = $this->dzangocart
                ->updateCategory($category->getData());

            // TODO Display flash success message.
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    protected function getDefaultOptions(array $options = null)
    {
        return array(
            'csrf_protection' => false
        );
    }
}
