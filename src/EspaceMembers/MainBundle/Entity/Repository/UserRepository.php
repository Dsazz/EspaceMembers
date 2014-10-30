<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('last_name' => 'ASC'));
    }

    public function findTeachers()
    {
        $qb = $this->createQueryBuilder('u')
                   ->select('partial u.{id, first_name, last_name, is_teacher}')
                   ->where('u.is_teacher = 1')
                   ->orderBy('u.last_name', 'ASC');

        return $qb->getQuery()->getResult();
    }

}
