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
            ->orderBy('t.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
