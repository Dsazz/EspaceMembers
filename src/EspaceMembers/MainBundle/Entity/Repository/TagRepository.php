<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findAll()
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('partial t.{id, title}')
            ->orderBy('t.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
