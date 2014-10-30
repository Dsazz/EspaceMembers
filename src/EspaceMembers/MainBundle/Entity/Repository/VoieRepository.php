<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class VoieRepository extends EntityRepository
{
    //public function findAll()
    //{
        //return $this->findBy(array(), array('title' => 'ASC'));
    //}
    public function findAll()
    {
        $qb = $this->createQueryBuilder('v')
                   ->select('partial v.{id, title}')
                   ->orderBy('v.title', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
