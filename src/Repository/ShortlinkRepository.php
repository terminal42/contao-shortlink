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
     * @var Hashids
     */
    private $hashids;

    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry, Hashids $hashids)
    {
        parent::__construct($registry, Shortlink::class);

        $this->hashids = $hashids;
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
                    AND sl.published='1'
            ")
            ->setParameter('alias', $alias)
            ->setParameter('id', $ids[0] ?? 0)
            ->getResult();
    }
}
