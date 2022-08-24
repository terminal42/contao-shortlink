<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

/**
 * @Hook("replaceInsertTags")
 */
class InsertTagsListener
{
    private ShortlinkGenerator $generator;
    private ShortlinkRepository $repository;

    public function __construct(ShortlinkGenerator $generator, ShortlinkRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function __invoke(string $tag)
    {
        $chunks = explode('::', $tag);

        if ('shortlink' === $chunks[0]) {
            /** @var Shortlink $link */
            $link = $this->repository->find((int) $chunks[1]);

            if (null === $link) {
                return '';
            }

            return '//'.$this->generator->generateFromEntity($link);
        }

        return false;
    }
}
