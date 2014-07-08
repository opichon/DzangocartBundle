<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\CategoryFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CatalogueController
{
    protected $dzangocart;
    protected $form_factory;
    protected $request_stack;
    protected $http_kernel;

    public function __construct($dzangocart, FormFactory $form_factory, RequestStack $request_stack, HttpKernel  $http_kernel)
    {
        $this->dzangocart = $dzangocart;
        $this->form_factory = $form_factory;
        $this->request_stack = $request_stack;
        $this->http_kernel = $http_kernel;
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

        $form->handleRequest($request);

        if( $form ->isValid()){
            $path = array('category' => $category);
            $path['_controller'] = 'dzangocart.controller.catalogue:updateAction';
            $subRequest = $this->request_stack->getCurrentRequest()->duplicate(array(), null, $path);

            return $this->http_kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Template
     */
    public function updateAction(Request $request)
    {
        echo "<pre>";
        print_r($request);
        die;
        //return array();
    }
}
