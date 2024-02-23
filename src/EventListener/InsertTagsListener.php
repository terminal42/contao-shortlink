<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

#[AsHook('replaceInsertTags')]
class InsertTagsListener
{
    public function __construct(
        private readonly ShortlinkGenerator $generator,
        private readonly ShortlinkRepository $repository,
    ) {
    }

    public function __invoke(string $tag): bool|string
    {
        $chunks = explode('::', $tag);

        if ('shortlink' === $chunks[0]) {
            /** @var Shortlink $link */
            $link = $this->repository->find((int) $chunks[1]);

            if (null === $link) {
                return '';
            }

            return $this->generator->generate($link->getId(), $link->getAlias());
        }

        return false;
    }
}
