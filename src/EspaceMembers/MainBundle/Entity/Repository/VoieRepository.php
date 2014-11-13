<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class VoieRepository extends EntityRepository
{
    public function getTitles()
    {
        return $qb = $this->createQueryBuilder('v')
            ->select('partial v.{id, title}')
            ->innerJoin('v.teachings','tch', 'WITH', 'tch.is_show = 1')
            ->innerJoin('tch.event','ev')
            ->groupBy('v.id')
            ->having('COUNT(tch.id) > 0 AND COUNT(ev.id) > 0')
            ->orderBy('v.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
