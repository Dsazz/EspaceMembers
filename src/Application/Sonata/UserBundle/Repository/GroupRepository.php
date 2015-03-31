<?php

namespace Application\Sonata\UserBundle\Repository;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function findAllNames()
    {
        return $qb = $this->createQueryBuilder('g')
            ->select('partial g.{id, name}')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
