<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Hashids\Hashids;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

class ShortlinkRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry, private readonly Hashids $hashids)
    {
        parent::__construct($registry, Shortlink::class);
    }

    /**
     * @return array<Shortlink>
     */
    public function findRouteCandidatesByAlias(string $alias)
    {
        $ids = $this->hashids->decode($alias);

        return $this->getEntityManager()
            ->createQuery(/** @lang DQL */ "
                SELECT sl
                FROM Terminal42\\ShortlinkBundle\\Entity\\Shortlink sl
                WHERE (
                        LOWER(sl.alias)=:alias
                        OR (sl.alias='' AND sl.id=:id)
                    )
                    AND sl.published='1'
            ")
            ->setParameter('alias', strtolower($alias))
            ->setParameter('id', $ids[0] ?? 0)
            ->getResult()
        ;
    }
}
