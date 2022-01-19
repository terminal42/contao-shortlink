<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\ContaoManager;

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Terminal42\ShortlinkBundle\Terminal42ShortlinkBundle;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(Terminal42ShortlinkBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /**
     * Allows a plugin to load container configuration.
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $isNew = InstalledVersions::satisfies(new VersionParser(), 'symfony/doctrine-bridge', '^5.4');
            $bundleDir = $isNew ? 'src/Entity' : 'Entity';

            $container->loadFromExtension('doctrine', [
                'orm' => [
                    'mappings' => [
                        'Terminal42ShortlinkBundle' => [
                            'is_bundle' => true,
                            'type' => 'annotation',
                            'dir' => $bundleDir
                        ]
                    ]
                ]
            ]);
        });
    }
}
