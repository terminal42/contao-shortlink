<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Terminal42\ShortlinkBundle\Terminal42ShortlinkBundle;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        if (!class_exists(\Doctrine\ORM\Version::class)) {
            return [];
        }

        return [
            BundleConfig::create(Terminal42ShortlinkBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

    /**
     * Allows a plugin to load container configuration.
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load(__DIR__.'/../Resources/config/orm.yml');
    }
}
