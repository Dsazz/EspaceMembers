<?php

namespace EspaceMembers\MainBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class ChronologyRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('year' => 'ASC'));
    }
}
