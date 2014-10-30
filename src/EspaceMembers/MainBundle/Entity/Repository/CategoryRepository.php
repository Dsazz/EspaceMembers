<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    //public function findAll()
    //{
        //return $this->findBy(array(), array('title' => 'ASC'));
    //}
    public function findAll()
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('partial c.{id, title}')
                   ->orderBy('c.title', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
