<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Exception;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class CatalogueController
{
    protected $dzangocart;
    protected $form_factory;
    protected $router;
    protected $translator;

    public function __construct($dzangocart, FormFactory $form_factory, Router $router, TranslatorInterface $translator)
    {
        $this->dzangocart = $dzangocart;
        $this->form_factory = $form_factory;
        $this->router = $router;
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
            $request->getSession()->getFlashBag()->add(
                'Category.error',
                $this->translator->trans('category.error.not_found', array(), 'dzangocart', $request->getLocale())
            );

            return new RedirectResponse($this->router->generate('dzangocart_catalogue'));
        }

        $form = $this->form_factory->create(
            new CategoryFormType(),
            $category,
            array(
                'action' => $this->router->generate(
                    'dzangocart_category_update',
                    $params
                )
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
            array(
                'action' => $this->router->generate(
                    'dzangocart_category_update',
                    array('id' => $id)
                )
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $category = $this->dzangocart
                    ->updateCategory($this->processFormData($category->getData()));

                // TODO Display flash success message.
                $request->getSession()->getFlashBag()->add(
                    'Category.update.success',
                    $this->translator->trans('category.update.success', array(), 'dzangocart', $request->getLocale())
                );

                return new RedirectResponse($this->router->generate('dzangocart_category', array('id' => $id)));

            } catch(Exception $e){
                $request->getSession()->getFlashBag()->add(
                        'Category.error.update',
                        $this->translator->trans('category.error.update', array(), 'dzangocart', $request->getLocale())
                    );
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /*
     * Takes submitted form data, accepts only checked value from checkbox.
     */
    protected function processFormData($data = array())
    {
        $check_box_datas = array();
        $others = array();

        $keys = $this->getCheckBoxKeys();

        foreach ($data as $name => $value) {
            if (array_key_exists($name, $keys)) {
                if ($value) {
                    $check_box_datas[$name] = $value;
                }
            } else {
                $others[$name] = $value;
            }
        }

        return array_merge($check_box_datas, $others);
    }

    protected function getCheckBoxKeys()
    {
        return array(
            'taxIncluded' => 'taxIncluded',
            'export' => 'export',
            'download' => 'download',
            'shipping' => 'shipping',
            'pack' => 'pack'
        );
    }

}
