<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Picker;

use Contao\CoreBundle\Picker\AbstractInsertTagPickerProvider;
use Contao\CoreBundle\Picker\DcaPickerProviderInterface;
use Contao\CoreBundle\Picker\PickerConfig;

class ShortlinkPickerProvider extends AbstractInsertTagPickerProvider implements DcaPickerProviderInterface
{
    public function getName(): string
    {
        return 'shortlinkPicker';
    }

    /**
     * Do not declare argument type for compatibility with Contao 4.13
     */
    public function supportsContext(/* string */ $context): bool
    {
        return 'link' === $context;
    }

    public function supportsValue(PickerConfig $config): bool
    {
        return $this->isMatchingInsertTag($config);
    }

    public function getDcaTable(PickerConfig|null $config = null): string
    {
        return 'tl_terminal42_shortlink';
    }

    public function getDcaAttributes(PickerConfig $config): array
    {
        $attributes = ['fieldType' => 'radio'];

        if ($this->supportsValue($config)) {
            $attributes['value'] = $this->getInsertTagValue($config);

            if ($flags = $this->getInsertTagFlags($config)) {
                $attributes['flags'] = $flags;
            }
        }

        return $attributes;
    }

    public function convertDcaValue(PickerConfig $config, mixed $value): string
    {
        return sprintf($this->getInsertTag($config), $value);
    }

    protected function getRouteParameters(PickerConfig|null $config = null): array
    {
        return ['do' => 'shortlink'];
    }

    protected function getDefaultInsertTag(): string
    {
        return '{{shortlink::%s}}';
    }
}
