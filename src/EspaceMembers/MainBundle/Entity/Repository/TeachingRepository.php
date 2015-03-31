<?php

namespace EspaceMembers\MainBundle\Entity\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

class TeachingRepository extends EntityRepository
{
    public function findPartialOneById($teaching_id)
    {
        return $qb = $this->createQueryBuilder('tch')
            ->select('tch')
            ->addSelect('partial lsn.{id, contentType, context, providerName, providerStatus, providerReference}')
            ->addSelect('partial u.{id, lastname, firstname}')
            ->addSelect('v')
            ->addSelect('tags')
            ->innerJoin('tch.lesson', 'lsn')
            ->innerJoin('tch.users', 'u')
            ->innerJoin('tch.directions', 'v')
            ->innerJoin('tch.tags', 'tags')
            ->where('tch.is_show = 1 AND tch.id = :teaching_id')
            ->setParameter("teaching_id", $teaching_id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
