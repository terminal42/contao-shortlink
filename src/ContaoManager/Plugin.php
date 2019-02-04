<?php

namespace Terminal42\ShortlinkBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Terminal42\ShortlinkBundle\Terminal42ShortlinkBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        if (!class_exists(\Doctrine\ORM\Version::class)) {
            return [];
        }

        return [new BundleConfig(Terminal42ShortlinkBundle::class)];
    }
}
