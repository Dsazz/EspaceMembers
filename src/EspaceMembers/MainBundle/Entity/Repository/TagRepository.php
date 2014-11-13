<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class TagRepository extends EntityRepository
{
    public function getTitles()
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('partial t.{id, title}')
            //->innerJoin('t.teachings','tch', 'WITH', 'tch.is_show = 1')
            //->innerJoin('tch.event','tchev')
            //->innerJoin('t.events','ev')
            //->innerJoin('ev.users','u', 'WITH', 'u.is_teacher = 1')
            //->groupBy('t.id')
            //->having('COUNT(tchev.id) > 0 OR COUNT(u.id) > 0')
            ->orderBy('t.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
