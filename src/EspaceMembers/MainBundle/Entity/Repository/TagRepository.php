<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    //public function findAll()
    //{
        //return $this->findBy(array(), array('title' => 'ASC'));
    //}

    public function findAll()
    {
        $qb = $this->createQueryBuilder('t')
                   ->select('partial t.{id, title}')
                   ->orderBy('t.title', 'ASC');

        return $qb->getQuery()->getResult();
    }


}
