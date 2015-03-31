<?php

namespace EspaceMembers\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class TagRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('partial t.{id, name}')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
