<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->ignoreErrorsOnPackage('doctrine/collections', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('doctrine/dbal', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('doctrine/doctrine-bundle', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('doctrine/orm', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('doctrine/persistence', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony-cmf/routing', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/config', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/dependency-injection', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/framework-bundle', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/http-foundation', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/http-kernel', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/routing', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/security-core', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPackage('symfony/translation-contracts', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnExtension('ext-imagick', [ErrorType::SHADOW_DEPENDENCY])
;
