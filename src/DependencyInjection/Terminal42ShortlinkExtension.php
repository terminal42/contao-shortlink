<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Terminal42\ShortlinkBundle\Controller\ShortlinkController;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

class Terminal42ShortlinkExtension extends ConfigurableExtension
{
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config'),
        );

        $loader->load('services.yml');

        $container
            ->getDefinition(ShortlinkController::class)
            ->setArgument(2, (bool) $config['log_ip'])
        ;

        $container
            ->getDefinition(ShortlinkGenerator::class)
            ->setArgument(2, $config['host'])
        ;

        $container
            ->getDefinition('terminal42_shortlink.hashids')
            ->setArgument(0, $config['salt'])
        ;

        $container
            ->getDefinition('terminal42_shortlink.routing.route_provider')
            ->setArgument(2, $config['host'])
            ->setArgument(3, $config['catchall_redirect'])
        ;
    }
}
