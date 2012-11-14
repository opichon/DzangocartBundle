<?php
namespace Dzangocart\Bundle\DzangocartBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Dzangocart\Bundle\DzangocartBundle\Service\Dzangocart;
use Dzangocart\Client\DzangocartClient;

class DzangocartExtension extends \Twig_Extension
{
	protected $container;
	protected $dzangocart;
	
	public function __construct(ContainerInterface $container, Dzangocart $dzangocart)
	{
		$this->container = $container;
		$this->dzangocart = $dzangocart;
	}

	public function getName()
    {
        return 'dzangocart';
    }
    
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			'add_to_cart' => new \Twig_Function_Method($this, 'add_to_cart', array('is_safe' => array('html'))),
		);
	}
    
    public function add_to_cart($product, $price, $quantity = 1,
                                $category = 'default', $options = array(),
                                $checkout = false, $label = null, 
                                $html_options = array(), $customer_data = array())
    {
        if (!$label) {
            $label = $this->dzangocart->getAddToCartLabel();
        }
        
        $html_options = array_merge(array('title' => $this->dzangocart->getAddToCartTitle()), 
                                    $html_options);
        
        $html_options['class'] = array_key_exists('class', $html_options) ?
                                    'dzangocart ' . $html_options['class'] :
                                    'dzangocart';        
        
        
        $html_options['href'] = $this->getCartUrl($product, $price, $quantity, 
                                                  $category, $options, $checkout);
        
        $attributes = "";
        foreach ($html_options as $key => $value) {
            $attributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        $this->setCustomerData($customer_data);
        
        return '<a' . $attributes . '>' . $label . '</a>';
    }
    
    protected function getCartUrl($product, $price, $quantity = 1,
                                  $category = 'default', $options = array(),
                                  $checkout = false)
    {
        $url = $this->dzangocart->getUrl();
        $url .= '/cart/show?';
        
        $params = array('name' => is_object($product) ? $product->__toString() : $product,
                        'price' => $price);
        
        if ($quantity) { $params['quantity'] = $quantity; }
        if ($category) { $params['category'] = $category; }        
        if ($checkout) { $params['checkout'] = true; }

        if (!array_key_exists('test', $options) && $test = $this->dzangocart->getTestCode()) {
            $options['test'] = $test;
        }
        
        $params = array_merge($params, $options);
        
        return $url . http_build_query($params);
    }
    
    protected function setCustomerData($customer_data)
    {
        if (!empty($customer_data)) {
            $data = DzangocartClient::encode($customer_data, 
                                             $this->dzangocart->getSecretKey(), 
                                             3600);
            setcookie('dzangocart', $data, null, '/');
        } else {
            setcookie('dzangocart', "", time() - 3600, '/');
        }
    }
}

