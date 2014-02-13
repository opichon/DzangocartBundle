<?php

namespace Dzangocart\Bundle\DzangocartBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dzangocart');

        $rootNode
            ->children()
                ->scalarNode('cart_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('secret_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('test_code')
                    ->defaultValue('')
                ->end()
                ->arrayNode('add_to_cart')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('label')
                            ->defaultValue('Add to cart')
                        ->end()
                        ->scalarNode('title')
                            ->defaultValue('Shopping cart')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sips')
                    ->children()
                        ->scalarNode('merchant_id')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('date_format')
                    ->defaultValue('dd/MM/yyyy')
                ->end()
                ->scalarNode('datetime_format')
                    ->defaultValue('dd/MM/yyyy HH:mm')
                ->end()
                ->scalarNode('api_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
