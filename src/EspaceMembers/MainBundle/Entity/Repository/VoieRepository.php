<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class VoieRepository extends EntityRepository
{
    public function findAll()
    {
        return $qb = $this->createQueryBuilder('v')
            ->select('partial v.{id, title}')
            ->orderBy('v.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
