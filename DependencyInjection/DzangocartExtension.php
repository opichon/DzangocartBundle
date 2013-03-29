<?php

namespace Dzangocart\Bundle\DzangocartBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DzangocartExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $container->setParameter(
            'dzangocart.config', 
            $config
        );


/*        $container->setParameter('dzangocart.url',          $config['url']);
        $container->setParameter('dzangocart.secret_key',   $config['secret_key']);
        $container->setParameter('dzangocart.test_code',    $config['test_code']);
        
        $container->setParameter('dzangocart.add_to_cart.label',    $config['add_to_cart']['label']);
        $container->setParameter('dzangocart.add_to_cart.title',    $config['add_to_cart']['title']);
*/

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
