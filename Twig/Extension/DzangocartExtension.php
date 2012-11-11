<?php
namespace Dzangocart\DangocartBundle\Twig\Extension;

use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

class DzangocartExtension extends \Twig_Extension
{

	protected $loader;
	protected $controller;

	public function __construct(FilesystemLoader $loader)
	{
		$this->loader = $loader;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			'code' => new \Twig_Function_Method($this, 'add_to_cart', array('is_safe' => array('html'))),
		);
	}
}

