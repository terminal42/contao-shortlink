<?php

namespace Terminal42\ShortlinkBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

class ShortlinkRepository extends ServiceEntityRepository
{

    /**
     * Constructor.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Shortlink::class);
    }

    /**
     * @return Shortlink[]
     */
    public function findRouteCandidatesByAlias(string $alias)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT sl FROM Shortlink sl WHERE published='1' AND (alias=? OR alias='')")
            ->setParameters([$alias])
            ->getResult();
    }
}
