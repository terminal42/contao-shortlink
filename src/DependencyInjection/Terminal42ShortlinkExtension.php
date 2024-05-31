<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Terminal42\ShortlinkBundle\Controller\ShortlinkController;
use Terminal42\ShortlinkBundle\EventListener\DataContainer\ShortlinkAliasListener;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

class Terminal42ShortlinkExtension extends ConfigurableExtension
{
    /**
     * @param array<string, mixed> $mergedConfig
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config'),
        );

        $loader->load('services.yml');

        $container
            ->getDefinition(ShortlinkController::class)
            ->setArgument(2, (bool) $mergedConfig['log_ip'])
        ;

        $container
            ->getDefinition(ShortlinkGenerator::class)
            ->setArgument(3, $mergedConfig['host'])
            ->setArgument(4, $mergedConfig['prefix'])
        ;

        $container
            ->getDefinition('terminal42_shortlink.hashids')
            ->setArgument(0, $mergedConfig['salt'])
            ->setArgument(1, $mergedConfig['min_length'])
        ;

        $container
            ->getDefinition(ShortlinkAliasListener::class)
            ->setArgument(1, $mergedConfig['min_length'])
        ;

        $container
            ->getDefinition('terminal42_shortlink.routing.route_provider')
            ->setArgument(2, $mergedConfig['host'])
            ->setArgument(3, $mergedConfig['prefix'])
            ->setArgument(4, $mergedConfig['catchall_redirect'])
        ;
    }
}
