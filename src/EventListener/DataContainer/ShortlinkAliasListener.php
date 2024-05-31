<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCallback(table: 'tl_terminal42_shortlink', target: 'fields.alias.save')]
class ShortlinkAliasListener
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly int $minHashLength = 0,
    ) {
    }

    public function __invoke(string $value, DataContainer $dc): string
    {
        if ('' !== $value && \strlen($value) < $this->minHashLength) {
            throw new \RuntimeException($this->translator->trans('ERR.minlength', [$GLOBALS['TL_DCA']['tl_terminal42_shortlink']['fields']['alias']['label'][0] ?? 'alias', $this->minHashLength], 'contao_default'));
        }

        return $value;
    }
}
