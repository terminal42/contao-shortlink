<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Repository;

use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Hashids\Hashids;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

class ShortlinkRepository extends ServiceEntityRepository
{
    /**
     * @var Hashids
     */
    private $hashids;

    /**
     * @var TokenChecker
     */
    private $tokenChecker;

    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry, Hashids $hashids, TokenChecker $tokenChecker)
    {
        parent::__construct($registry, Shortlink::class);

        $this->hashids = $hashids;
        $this->tokenChecker = $tokenChecker;
    }

    /**
     * @return Shortlink[]
     */
    public function findRouteCandidatesByAlias(string $alias)
    {
        $ids = $this->hashids->decode($alias);

        return $this->getEntityManager()
            ->createQuery(/** @lang DQL */"
                SELECT sl 
                FROM Terminal42\ShortlinkBundle\Entity\Shortlink sl 
                WHERE (
                        sl.alias=:alias
                        OR (sl.alias='' AND sl.id=:id)
                    )
                    ".($this->tokenChecker->isPreviewMode() ? "AND sl.published='1'" : '')."
            ")
            ->setParameter('alias', $alias)
            ->setParameter('id', $ids[0] ?? 0)
            ->getResult();
    }
}
