<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

    public function findName()
    {
        return $qb = $this->createQueryBuilder('g')
            ->select('partial g.{id, name}')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
