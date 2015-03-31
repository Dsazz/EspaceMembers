<?php

namespace EspaceMembers\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class DirectionRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('v')
            ->select('partial v.{id, name}')
            ->innerJoin('v.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('tch.event','ev')
            ->groupBy('v.id')
            ->having('COUNT(tch.id) > 0 AND COUNT(ev.id) > 0')
            ->orderBy('v.name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
