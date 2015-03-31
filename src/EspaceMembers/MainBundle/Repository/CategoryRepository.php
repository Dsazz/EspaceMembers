<?php

namespace EspaceMembers\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class CategoryRepository extends EntityRepository
{
    public function getNames()
    {
        return $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, name}')
            ->innerJoin('c.events', 'ev')
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}