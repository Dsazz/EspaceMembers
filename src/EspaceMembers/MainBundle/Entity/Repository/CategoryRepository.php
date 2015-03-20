<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\ApcCache;

class CategoryRepository extends EntityRepository
{
    public function getTitles()
    {
        return $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, title}')
            ->innerJoin('c.events', 'ev')
            ->innerJoin('ev.users', 'u', 'WITH', 'u.is_teacher = 1')
            ->innerJoin('u.teachings', 'tch', 'WITH', 'tch.is_show = 1')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
