<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('terminal42_shortlink');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('terminal42_shortlink');
        }

        $rootNode
            ->children()
                ->scalarNode('host')->defaultValue('')->end()
                ->scalarNode('salt')->defaultValue('terminal42_shortlink')->end()
                ->booleanNode('log_ip')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
