<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAll()
    {
        return $qb = $this->createQueryBuilder('c')
            ->select('partial c.{id, title}')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
