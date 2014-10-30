<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

}
