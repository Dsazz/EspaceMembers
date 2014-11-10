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
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
