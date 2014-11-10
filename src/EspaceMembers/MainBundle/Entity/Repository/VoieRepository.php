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
            ->orderBy('v.title', 'ASC')
            ->getQuery()
            ->useResultCache(true, 3600)
            ->getResult();
    }
}
